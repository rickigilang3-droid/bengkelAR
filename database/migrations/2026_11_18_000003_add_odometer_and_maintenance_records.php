<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->unsignedInteger('current_odometer_km')->nullable()->after('description');
        });

        Schema::create('maintenance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->string('part_name', 120);
            $table->date('replaced_at');
            $table->unsignedInteger('odometer_km');
            $table->unsignedInteger('interval_km')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['vehicle_id', 'part_name', 'replaced_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_records');

        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn('current_odometer_km');
        });
    }
};
