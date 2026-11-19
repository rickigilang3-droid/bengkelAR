<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_queues', function (Blueprint $table) {
            $table->unsignedInteger('odometer_km')->nullable()->after('notes');
        });

        Schema::table('service_approval_items', function (Blueprint $table) {
            $table->foreignId('inventory_id')->nullable()->after('service_queue_id')->constrained()->nullOnDelete();
            $table->foreignId('requested_by')->nullable()->after('inventory_id')->constrained('users')->nullOnDelete();
            $table->unsignedInteger('quantity')->default(1)->after('estimated_cost');
            $table->unsignedInteger('odometer_km')->nullable()->after('quantity');
            $table->unsignedInteger('interval_km')->nullable()->after('odometer_km');
        });
    }

    public function down(): void
    {
        Schema::table('service_approval_items', function (Blueprint $table) {
            $table->dropForeign(['inventory_id']);
            $table->dropForeign(['requested_by']);
            $table->dropColumn(['inventory_id', 'requested_by', 'quantity', 'odometer_km', 'interval_km']);
        });

        Schema::table('service_queues', function (Blueprint $table) {
            $table->dropColumn('odometer_km');
        });
    }
};
