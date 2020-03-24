<?php declare(strict_types=1);

namespace App\Providers;

use App\Events\SearchJobCompleted;
use App\Listeners\HotelCacheListener;

/**
 * Class EventServiceProvider
 *
 * @package App\Providers
 */
class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        SearchJobCompleted::class => [
            HotelCacheListener::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot(): void
    {
        parent::boot();
    }
}
