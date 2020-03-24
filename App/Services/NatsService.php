<?php declare(strict_types=1);

namespace App\Services;

use Exception;
use Nats\Connection as Client;
use Nats\ConnectionOptions;

/**
 * @package App\Services
 *
 * Сервис работы с nats
 */
class NatsService
{
    private $client;

    /**
     * NatsService constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $options = new ConnectionOptions();
        $options->setHost(env('NATS_HOST', '127.0.0.1'));
        $options->setPort(env('NATS_PORT', '4222'));
        $options->setUser(env('NATS_USER', 'ruser'));
        $options->setPass(env('NATS_PASS', 'T0pS3cr3t'));


        $this->client = new Client($options);
        $this->client->connect();
    }

    /**
     * Метод публикации сообщения в Nats с учетом топика
     *
     * @param string $subject топик в который отправляется сообщение
     * @param array $messages массив сообщений которое будет закодированно в json для отправки
     * @throws Exception
     */
    public function publish(string $subject, array $messages): void
    {
        $this->client->ping();
        $this->client->publish($subject, serialize($messages));
    }

}