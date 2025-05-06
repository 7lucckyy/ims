<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('vehicle_movements', function (Blueprint $table) {
            $table->string('expected_departure_time')->nullable();
            $table->enum('approval', \App\Enums\Enums\VehicleStatus::value())->default(\App\Enums\Enums\VehicleStatus::Pending->value);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicle_movements', function (Blueprint $table) {
            $table->dropColumn('expected_departure_time');
            $table->dropColumn('approval');
        });
    }
};
