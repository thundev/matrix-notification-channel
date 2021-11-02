<?php

namespace Thundev\MatrixNotificationChannel\app\Services;

use JetBrains\PhpStorm\ArrayShape;

class MatrixMessage
{
    private string $roomId;
    private string $message;

    public function to(string $roomId): static
    {
        $this->roomId = $roomId;

        return $this;
    }

    public function message(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getRoomId(): string
    {
        return $this->roomId;
    }

    #[ArrayShape([
        'format' => "string",
        'msgtype' => "string",
        'body' => "string",
        'formatted_body' => "string"
    ])]
    public function getMessage(): array
    {
        return [
            'format' => 'org.matrix.custom.html',
            'msgtype' => 'm.text',
            'body' => strip_tags($this->message),
            'formatted_body' => nl2br($this->message),
        ];
    }
}
