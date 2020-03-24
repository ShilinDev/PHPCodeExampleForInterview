<?php declare(strict_types=1);

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use ProjectName\Engine2\components\SearchBuilder;
use Illuminate\Foundation\Events\Dispatchable;

/**
 * Class SearchJobCompleted
 * @package App\Events
 *
 * Событие завершения поиска
 */
class SearchJobCompleted
{
    use Dispatchable, SerializesModels;

    /**
     * @var SearchBuilder $searchBuilder
     */
    public $searchBuilder;

    /**
     * Размещения храняться в сериализованном и компрессированном виде
     *
     * @var string $stays
     */
    private $stays;

    /**
     * @param array $stays
     */
    public function setStays($stays): void
    {
        $this->stays = base64_encode(gzcompress(serialize($stays), 6));
    }

    /**
     * Метод получения размещений после десереализации и декомпрессии данных
     *
     * @return array
     */
    public function getStays()
    {
        return unserialize(gzuncompress(base64_decode($this->stays)),['allowed_classes' => false]) ?: [];
    }

    /**
     * Конструктор события завершения поиска
     *
     * @param SearchBuilder $searchBuilder
     * @param array $stays
     */
    public function __construct(SearchBuilder $searchBuilder, array $stays)
    {
        $this->searchBuilder = $searchBuilder;
        $this->setStays($stays);
    }
}
