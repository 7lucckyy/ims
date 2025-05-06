<?php

use App\Enums\AppraisalCycle;
use App\Enums\AppraisalMethod;
use App\Models\Project;
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
        Schema::create('appraisals', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Project::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('staff_id')->references('id')->on('users')->cascadeOnDelete();
            $table->enum('cycle', AppraisalCycle::values())->default(AppraisalCycle::DEFAULT);
            $table->enum('method', AppraisalMethod::values())->default(AppraisalMethod::DEFAULT);
            $table->json('evaluation_criteria')->nullable();
            $table->json('staff_input')->nullable();
            $table->text('feedback')->nullable();
            $table->text('discussion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appraisals');
    }
};
