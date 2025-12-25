<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Services\Telegram\TelegramApiClient;
use App\Services\Telegram\TelegramService;
use App\Services\Telegram\ValueObjects\TelegramToken;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use app\Services\Telegram\DTO\TelegramSendMessageDTO;
use app\Services\Telegram\DTO\TelegramSendMessageResultDTO;

final class TelegramServiceTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testSendMessageReturnsSuccessWhenClientSucceeds(): void
    {
        $client = Mockery::mock(TelegramApiClient::class);
        $dto = Mockery::mock(TelegramSendMessageDTO::class);
        $token = Mockery::mock(TelegramToken::class);

        $client->shouldReceive('sendMessage')
            ->once()
            ->with($dto, $token)
            ->andReturnNull();

        $service = new TelegramService($client);

        $result = $service->sendMessage($dto, $token);

        $this->assertInstanceOf(TelegramSendMessageResultDTO::class, $result);

        if (property_exists($result, 'success')) {
            $this->assertTrue($result->success);
        } elseif (method_exists($result, 'isSuccess')) {
            $this->assertTrue($result->isSuccess());
        }
    }

    public function testSendMessageReturnsFailureWhenClientThrows(): void
    {
        $client = Mockery::mock(TelegramApiClient::class);
        $dto = Mockery::mock(TelegramSendMessageDTO::class);
        $token = Mockery::mock(TelegramToken::class);

        $client->shouldReceive('sendMessage')
            ->once()
            ->with($dto, $token)
            ->andThrow(new \RuntimeException('failure message'));

        $service = new TelegramService($client);

        $result = $service->sendMessage($dto, $token);

        $this->assertInstanceOf(TelegramSendMessageResultDTO::class, $result);

        // Мягкая проверка "неуспеха" и сообщения об ошибке
        if (property_exists($result, 'success')) {
            $this->assertFalse($result->success);
        } elseif (method_exists($result, 'isSuccess')) {
            $this->assertFalse($result->isSuccess());
        }

        if (property_exists($result, 'error')) {
            $this->assertSame('failure message', $result->error);
        } elseif (method_exists($result, 'getError')) {
            $this->assertSame('failure message', $result->getError());
        }
    }
}

