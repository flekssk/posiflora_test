<?php

declare(strict_types=1);

namespace App\Services\Telegram;

use App\Services\Telegram\DTO\TelegramSendMessageDTO;
use App\Services\ValueObjects\TelegramToken;
use GuzzleHttp\Client;

readonly class TelegramApiClient
{
    public function __construct(private Client $client)
    {
    }

    public function sendMessage(
        TelegramSendMessageDTO $message,
        TelegramToken $token
    ): ?array {
        $data = [
            'chat_id' => $message->chatId,
            'text' => $message->text,
            'parse_mode' => $message->markdown ? 'MarkdownV2' : 'HTML'
        ];

        if ($message->keyboard) {
            $data['reply_markup'] = json_encode($message->keyboard);
        }

        return $this->sendRequest($token, 'sendMessage', $data);
    }

    protected function sendRequest(TelegramToken $token, string $method, array $data = []): ?array
    {
        $response = $this->client->post(
            "bot$token/$method",
            [
                'json' => $data,
            ]
        );

        return json_decode($response->getBody()->getContents(), true);
    }
}
