<?php

use App\Models\BudgetTrench;
use App\Models\Location;
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
        Schema::create('terms_of_references', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Project::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Location::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(BudgetTrench::class)->constrained()->cascadeOnDelete();
            $table->string('duty_station');
            $table->foreignId('budget_holder')->references('id')->on('users')->cascadeOnDelete();
            $table->longText('background');
            $table->longText('justification');
            $table->longText('project_output');
            $table->longText('activity_objectives');
            $table->longText('activity_expected_output');
            $table->longText('micro_activities');
            $table->longText('modalities_of_implementation');
            $table->json('budget');
            $table->integer('total');
            $table->foreignId('prepared_by')->references('id')->on('users')->cascadeOnDelete();
            $table->foreignId('reviewed_by')->nullable()->references('id')->on('users')->cascadeOnDelete();
            $table->foreignId('request_review')->nullable()->references('id')->on('users')->cascadeOnDelete();
            $table->foreignId('approved_by')->nullable()->references('id')->on('users')->cascadeOnDelete();
            $table->foreignId('request_approval')->nullable()->references('id')->on('users')->cascadeOnDelete();
            $table->foreignId('authorized_by')->nullable()->references('id')->on('users')->cascadeOnDelete();
            $table->foreignId('request_authorization')->nullable()->references('id')->on('users')->cascadeOnDelete();
            $table->foreignId('confirmed_by')->nullable()->references('id')->on('users')->cascadeOnDelete();
            $table->foreignId('request_confirmation')->nullable()->references('id')->on('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('terms_of_references');
    }
};
