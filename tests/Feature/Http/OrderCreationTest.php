<?php

declare(strict_types=1);

namespace Feature\Http;

use App\Enums\TelegramSendMessageStatusEnum;
use App\Models\Shop;
use App\Models\TelegramIntegration;
use App\Models\User;
use App\Models\UserShop;
use App\Services\Orders\Actions\Telegram\OrderRetryTelegramNotificationAction;
use App\Services\Telegram\DTO\TelegramSendMessageDTO;
use app\Services\Telegram\DTO\TelegramSendMessageResultDTO;
use App\Services\Telegram\Facades\TelegramServiceFacade;
use App\Services\Telegram\ValueObjects\TelegramToken;
use Illuminate\Support\Str;
use Tests\TestCase;

class OrderCreationTest extends TestCase
{
    public function testCreateOrderWithoutTelegramIntegration()
    {
        TelegramServiceFacade::partialMock()
            ->shouldReceive('sendMessage')
            ->never();

        $user = User::factory()->create();
        $shop = $this->makeShop($user);

        $response = $this->post(
            route('orders.create', ['shopId' => $shop->id]),
            [
                'total' => 100.00,
                'customer_name' => 'Test Customer',
            ]
        );

        $response->assertSuccessful();

        $this->assertDatabaseHas('orders', [
            'shop_id' => $shop->id,
            'customer_name' => 'Test Customer',
            'total' => 100.00,
        ]);
    }

    public function testCreateOrderWithDisabledTelegramIntegration()
    {
        TelegramServiceFacade::partialMock()
            ->shouldReceive('sendMessage')
            ->never();

        $user = User::factory()->create();
        $shop = $this->makeShop($user);
        TelegramIntegration::factory()->create(['shop_id' => $shop->id, 'enabled' => false]);

        $response = $this->post(
            route('orders.create', ['shopId' => $shop->id]),
            [
                'total' => 100.00,
                'customer_name' => 'Test Customer',
            ]
        );

        $response->assertSuccessful();

        $this->assertDatabaseHas('orders', [
            'shop_id' => $shop->id,
            'customer_name' => 'Test Customer',
            'total' => 100.00,
        ]);
    }

    public function testCreateOrderWithEnabledTelegramIntegration()
    {
        $user = User::factory()->create();
        $shop = $this->makeShop($user);
        $integration = TelegramIntegration::factory()->create([
            'shop_id' => $shop->id,
            'bot_token' => Str::random(32),
            'chat_id' => 111,
            'enabled' => true
        ]);

        $response = $this->post(
            route('orders.create', ['shopId' => $shop->id]),
            [
                'total' => 100.00,
                'customer_name' => 'Test Customer',
            ]
        );

        $response->assertSuccessful();

        $this->assertDatabaseHas('orders', [
            'shop_id' => $shop->id,
            'customer_name' => 'Test Customer',
            'total' => 100.00,
        ]);

        TelegramServiceFacade::partialMock()
            ->shouldReceive('sendMessage')
            ->once()
            ->withArgs(function (TelegramSendMessageDTO $dto, TelegramToken $token) use ($integration) {
                return $dto->text === 'Has new order'
                    && $dto->chatId === $integration->chat_id
                    && $integration->token === $token;
            })
            ->andReturn(new TelegramSendMessageResultDTO(true, 'success'));

        $this->assertDatabaseHas('telegram_send_logs', [
            'telegram_integration_id' => $shop->id,
            'message' => 'success',
            'status' => TelegramSendMessageStatusEnum::SENT
        ]);
    }

    public function testCreateOrderWithEnabledTelegramIntegrationAndSendLogFailed()
    {
        $user = User::factory()->create();
        $shop = $this->makeShop($user);
        $integration = TelegramIntegration::factory()->create([
            'shop_id' => $shop->id,
            'bot_token' => Str::random(32),
            'chat_id' => 111,
            'enabled' => true
        ]);

        $response = $this->post(
            route('orders.create', ['shopId' => $shop->id]),
            [
                'total' => 100.00,
                'customer_name' => 'Test Customer',
            ]
        );

        $response->assertSuccessful();

        $this->assertDatabaseHas('orders', [
            'shop_id' => $shop->id,
            'customer_name' => 'Test Customer',
            'total' => 100.00,
        ]);

        TelegramServiceFacade::partialMock()
            ->shouldReceive('sendMessage')
            ->once()
            ->withArgs(function (TelegramSendMessageDTO $dto, TelegramToken $token) use ($integration) {
                return $dto->text === 'Has new order'
                    && $dto->chatId === $integration->chat_id
                    && $integration->token === $token;
            })
            ->andReturn(
                new TelegramSendMessageResultDTO(false, 'fail')
            );

        $this->assertDatabaseHas('telegram_send_logs', [
            'telegram_integration_id' => $shop->id,
            'message' => 'fail',
            'status' => TelegramSendMessageStatusEnum::FAILED
        ]);

        TelegramServiceFacade::partialMock()
            ->shouldReceive('sendMessage')
            ->once()
            ->withArgs(function (TelegramSendMessageDTO $dto, TelegramToken $token) use ($integration) {
                return $dto->text === 'Has new order'
                    && $dto->chatId === $integration->chat_id
                    && $integration->token === $token;
            })
            ->andReturn(
                new TelegramSendMessageResultDTO(true, 'success')
            );

        OrderRetryTelegramNotificationAction::run();

        $this->assertDatabaseHas('telegram_send_logs', [
            'telegram_integration_id' => $shop->id,
            'message' => 'success',
            'status' => TelegramSendMessageStatusEnum::SENT
        ]);

    }

    private function makeShop(User $user): Shop
    {
        $shop = Shop::factory()->create();

        UserShop::create([
            'user_id' => $user->id,
            'shop_id' => $shop->id
        ]);

        return $shop;
    }
}
