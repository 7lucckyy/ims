<?php

use App\Models\Vendor;
use App\Models\BudgetDetail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('budget_trench_id')->constrained()->cascadeOnDelete();
            $table->foreignId('budget_details_id')->constrained()->cascadeOnDelete();
            $table->string('category_id');
            $table->date('transaction_date');
            $table->foreignIdFor(Vendor::class)->constrained()->cascadeOnDelete();
            $table->string('ref_number');
            $table->string('total_amount');
            $table->string('memo')->nullable();
            $table->string('attachment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
