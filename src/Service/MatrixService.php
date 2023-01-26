<?php

namespace Thundev\MatrixNotificationChannel\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Utils;
use GuzzleHttp\Exception\ClientException;
use InvalidArgumentException;
use GuzzleHttp\Exception\GuzzleException;
use Thundev\MatrixNotificationChannel\Contracts\MatrixServiceContract;
use Thundev\MatrixNotificationChannel\Message\MatrixMessage;

class MatrixService implements MatrixServiceContract
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
     * @param  MatrixMessage  $message
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
                'access_token' => $token,
            ],
        ]);
    }

    /**
     * @throws GuzzleException
     */
    public function createRoom(string $roomAlias = ''): ?string
    {
        try {
            $response = $this->client
                ->post('/_matrix/client/r0/createRoom', ['json' => ['room_alias' => $roomAlias]]);
        } catch (ClientException $exception) {
            return $exception->getCode() == 403
                ? false
                : throw $exception;
        }

        return Utils::jsonDecode($response
            ->getBody()
            ->getContents())?->room_id;
    }

    /**
     * @throws GuzzleException
     */
    public function leaveRoom(string $roomId): bool
    {
        $uri = sprintf('/_matrix/client/r0/rooms/%s/leave', $roomId);

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
    public function invite(string $username, string $roomId): bool
    {
        $uri = sprintf('/_matrix/client/r0/rooms/%s/invite', $roomId);

        try {
            $response = $this->client->post($uri, ['json' => ['user_id' => $username]]);
        } catch (ClientException $exception) {
            return $exception->getCode() == 403
                ? false
                : throw $exception;
        }

        return $response->getStatusCode() == 200;
    }
}
