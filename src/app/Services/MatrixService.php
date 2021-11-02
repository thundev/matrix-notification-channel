<?php

namespace Thundev\MatrixNotificationChannel\app\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use InvalidArgumentException;
use GuzzleHttp\Exception\GuzzleException;

class MatrixService implements MatrixServiceInterface
{
    private Client $client;

    public function __construct(string $uri, string $token)
    {
        if (empty($uri) || empty($token)) {
            throw new InvalidArgumentException('You have to provide both Element Uri and Token.');
        }

        $this->client = $this->getClient($uri, $token);
    }

    /**
     * @param MatrixMessage $message
     * @return bool
     * @throws GuzzleException
     */
    public function message(MatrixMessage $message): bool
    {
        $this->sendMessage($message->getRoomId(), ['json' => $message->getMessage()]);

        return true;
    }

    /**
     * @throws GuzzleException
     */
    public function joinRoom(string $roomId): bool
    {
        $uri = sprintf('/_matrix/client/r0/join/%s', $roomId);

        try {
            $response = $this->client->post($uri);
        } catch (ClientException $exception) {
            return $exception->getCode() == 403
                ? false
                : throw $exception;
        }

        return $response->getStatusCode() == 200;
    }

    /**
     * @throws GuzzleException
     */
    protected function sendMessage(string $roomId, array $body): bool
    {
        $eventId = uniqid('matrix_', true);
        $uri = sprintf('/_matrix/client/r0/rooms/%s/send/m.room.message/%s', $roomId, $eventId);

        try {
            $response = $this->client->put($uri, $body);
        } catch (GuzzleException $e) {
            if ($e->getCode() == 403 && $this->joinRoom($roomId)) {
                return $this->sendMessage($roomId, $body);
            }

            throw $e;
        }

        return $response->getStatusCode() == 200;
    }

    protected function getClient(string $uri, string $token): Client
    {
        return new Client([
            'verify' => false,
            'base_uri' => $uri,
            'query' => [
                'access_token' => $token
            ]
        ]);
    }
}
