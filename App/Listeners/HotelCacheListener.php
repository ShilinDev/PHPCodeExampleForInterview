<?php declare(strict_types=1);

namespace App\Listeners;

use App\Events\SearchJobCompleted;
use App\Services\HotelCacheService;
use Exception;
use HunterEngine;

/**
 * Class HotelCacheListener
 * @package App\Listeners
 *
 * Лисенер для событий связанных с поиском
 */
class HotelCacheListener
{
    /**
     * Обработчик события окончания поиска для сервиса Отели
     * Работает только в случае если включен прогрев кешей в конфигурации
     *
     * @param SearchJobCompleted $event объект события
     * @return void
     * @throws Exception
     */
    public function handle(SearchJobCompleted $event): void
    {
        if (config('hotelCache.enabled_warm_up_live_search', false)
            && ($event->searchBuilder->getEngineId() === HunterEngine::TYPE_HOTEL)) {
            (new HotelCacheService)->handle($event->searchBuilder->getRequest(), $event->getStays());
        }
    }

}
