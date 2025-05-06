<?php

use App\Enums\BidStatus;
use App\Models\Currency;
use App\Models\Project;
use App\Models\Vendor;
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
        Schema::create('competitive_bids', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Currency::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Project::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Vendor::class)->constrained()->cascadeOnDelete();
            $table->integer('bid_amount');
            $table->integer('our_bid_amount');
            $table->integer('variance_amount');
            $table->decimal('variance_percentage', places: 2);
            $table->date('bid_date');
            $table->enum('status', BidStatus::values())->default(BidStatus::DEFAULT);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competitive_bids');
    }
};
