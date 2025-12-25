<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('telegram_send_logs', function (Blueprint $table) {
            $table->integer('telegram_integration_id')->nullable()->after('chat_id');
        });
    }

    public function down(): void
    {
        Schema::table('telegram_send_logs', function (Blueprint $table) {
            $table->dropColumn('telegram_integration_id');
        });
    }
};
