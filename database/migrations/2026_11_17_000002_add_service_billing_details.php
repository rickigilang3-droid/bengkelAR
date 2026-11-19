<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_histories', function (Blueprint $table) {
            $table->foreignId('service_queue_id')
                ->nullable()
                ->unique()
                ->after('id')
                ->constrained('service_queues')
                ->nullOnDelete();
            $table->string('payment_channel', 30)->nullable()->after('payment_method');
        });
    }

    public function down(): void
    {
        Schema::table('service_histories', function (Blueprint $table) {
            $table->dropForeign(['service_queue_id']);
            $table->dropUnique(['service_queue_id']);
            $table->dropColumn(['service_queue_id', 'payment_channel']);
        });
    }
};
