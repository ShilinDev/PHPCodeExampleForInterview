<?php declare(strict_types=1);

namespace App\Jobs;

use App\Events\SearchJobCompleted;
use Exception;

/**
 * Class SearchJob
 * @package App\Jobs
 *
 * Задача выполнения поиска по одному чанку
 *
 */
class SearchJob extends Job
{

    /**
     * Обработка задачи поиска
     *
     * @return void
     * @throws Exception
     */
    public function handle(): void
    {
        if ($this->attempts() > 1) {
            $this->fail(new Exception('Задача запущена слишком много раз', $this->attempts()));
            return;
        }

        $this->log('Started', 'debug', [
            'convertedParams' => $this->params->getConvertedParams(),
        ]);

        try {
            // Скрытая бизнес логика
            $result = new SearchResult();

            event(new SearchJobCompleted($this->params, $result));
            // Скрытая бизнес логика, она кругом
        } catch (\Throwable $e) {
            static::logError($e);
        }
    }
}
