<?php declare(strict_types=1);

namespace App\Services;

use App\Builders\StayBuilder;
use App\Models\StayModel;
use Carbon\CarbonPeriod;
use Exception;
use HotelResult;
use Illuminate\Support\Facades\Log;
use SearchRQ;

/**
 * @package App\Services
 *
 * Сервис для бизнес логики обработки отельного кеша
 *
 */
class HotelCacheService
{
    /**
     * Название топика в натке, куда засылаем данные поисков
     */
    private const NATS_SEARCH_SUBJECT = 'cache';

    /**
     * @var array массив дат
     */
    private $dates = [];

    /**
     * @var StayModel[] массив размещений для отправки в nats
     */
    private $stays = [];

    /**
     * @var int $nights количество ночей учавствующих в поиске
     */
    private $nights = 0;

    /**
     * Базовый метод обработки поискового запроса и
     *
     * @param SearchRQ $hotelSearchRQ поисковый запрос
     * @param array $stays массив размещений из результата поиска
     * @throws Exception
     */
    public function handle(SearchRQ $hotelSearchRQ, array $stays): void
    {
        if ($this->shouldBeSaved($hotelSearchRQ)) {
            $this->generateDates($hotelSearchRQ);
            foreach ($stays as $hotelResult) {
                $this->prepareStays($hotelResult);
            }
            $this->publish();
        }
    }

    /**
     * Метод генерации партиций для хранения результатов и подсчет кол-ва ночей
     *
     * @param SearchRQ $hotelSearchRQ поисковой запрос
     */
    private function generateDates(SearchRQ $hotelSearchRQ): void
    {
        /** @var CarbonPeriod $period */
        $period = CarbonPeriod::create($hotelSearchRQ->checkInDate, $hotelSearchRQ->checkOutDate)->toArray();
        for ($i = 0; $i < (count($period) - 1); $i++) {
            $this->dates[] = ($period[$i]->format('Y-m-d'));
            $this->nights++;
        }
    }

    /**
     * Метод получение актуальной конфигурации прогрева кеша,
     * и проверки попадает ли текущий запрос в зону прогрева
     *
     * @param SearchRQ $hotelSearchRQ поисковый запрос
     * @return bool
     */
    private function shouldBeSaved(SearchRQ $hotelSearchRQ): bool
    {
        $shouldBeCachedRegions = config('hotelCache.should_be_cached_regions', []);
        if ($shouldBeCachedRegions) {
            return in_array((string)$hotelSearchRQ->placeId, $shouldBeCachedRegions, true);
        }

        return config('hotelCache.default_warm_up_live_search', false);
    }

    /**
     * Метод подготовки результатов поиска
     *
     * @param HotelResult $hotelResult Результат поиска по сервису Отели
     * @return void
     * @throws Exception
     */
    private function prepareStays($hotelResult): void
    {
        foreach ($this->dates as $date) {
            foreach ($hotelResult->results as $accommodation) {
                array_merge($this->stays, $this->getStays($accommodation, $date, $hotelResult));
            }
        }
    }

    /**
     * Получение моделей Stay
     *
     * @param HotelAccomodation $accommodation
     * @param string $date
     * @param HotelResult $hotelResult
     * @return StayModel[]
     */
    public function getStays(HotelAccomodation $accommodation, string $date, HotelResult $hotelResult): array
    {
        $stays = [];
        foreach ($accommodation->rooms as $room) {
            $roomHash = $this->getHash($room->name);
            $stays[] = StayBuilder::build(
                $date,
                (int)$hotelResult->cityId,
                (int)$hotelResult->id,
                ($room->adult . $room->child),
                (int)$room->mealId,
                $accommodation->supplierId,
                $roomHash,
                $accommodation->price / $this->nights,
                $accommodation->currency);
        }

        return $stays;
    }

    /**
     * Метод возвращает md5 хеш на основании названия типа комнаты
     *
     * @param string $roomTypeName тип комнаты
     * @return string
     */
    private function getHash(string $roomTypeName): string
    {
        $cleanString = preg_replace('/[^a-z_0-9]/', '', strtolower($roomTypeName));

        return md5($cleanString);
    }

    /**
     * Метод отдаёт результаты брокеру
     * @return void
     */
    private function publish(): void
    {
        try {
            if ($this->stays) {
                $nats = new NatsService();
                $nats->publish(self::NATS_SEARCH_SUBJECT, $this->stays);
            }
        } catch (Exception $exception) {
            Log::error('nats_publisher_error: ' . $exception->getMessage() . ' Trace: ' . $exception->getTraceAsString());
        }
    }
}