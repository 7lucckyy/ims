<?php

use App\Enums\BloodGroup;
use App\Models\Department;
use App\Models\Position;
use App\Models\User;
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
        Schema::create('staff_details', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Department::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Position::class)->constrained()->cascadeOnDelete();
            $table->date('dob')->nullable();
            $table->text('address')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('emergency_contact_number')->nullable();
            $table->enum('blood_group', BloodGroup::values());
            $table->date('date_of_employment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_details');
    }
};
