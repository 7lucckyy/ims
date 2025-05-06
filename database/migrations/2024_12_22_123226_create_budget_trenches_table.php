<?php

use App\Enums\BudgetTrenchStatus;
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
        Schema::create('budget_trenches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('code')->required();
            $table->integer('amount');
            $table->date('transaction_date');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', BudgetTrenchStatus::values())->default(BudgetTrenchStatus::DEFAULT);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_trenches');
    }
};
