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
        Schema::create('vehicle_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('department');
            $table->text('mission');
            $table->date('date');
            $table->string('day');
            $table->string('destination_from');
            $table->string('location_to');
            $table->string('expected_arrival');
            $table->string('location');
            $table->string('passenger');
            $table->string('luggage');
            $table->string('project');
            $table->string('remark')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_movements');
    }
};
