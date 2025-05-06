<?php

use App\Enums\DeliveryMethod;
use App\Enums\PRPriority;
use App\Enums\PurchaseStatus;
use App\Models\Currency;
use App\Models\Department;
use App\Models\Project;
use App\Models\State;
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
        Schema::create('purchase_requests', function (Blueprint $table) {
            $table->id();
            $table->string('pr_number');
            $table->foreignIdFor(Currency::class)->constrained()->cascadeOnDelete();
            $table->integer('procurement_threshold');
            $table->integer('sole_quotation');
            $table->integer('negotiated_procedures');
            $table->foreignIdFor(State::class)->constrained()->cascadeOnDelete();
            $table->enum('priority', PRPriority::values())->default(PRPriority::DEFAULT);
            $table->string('office');
            $table->foreignIdFor(Project::class)->constrained()->cascadeOnDelete();
            $table->date('request_date');
            $table->date('required_date');
            $table->date('end_date');
            $table->foreignIdFor(Department::class)->constrained()->cascadeOnDelete();
            $table->foreignId('requested_by')->nullable()->references('id')->on('users')->cascadeOnDelete();
            $table->enum('status', PurchaseStatus::values())->default(PurchaseStatus::DEFAULT);
            $table->json('items');
            $table->integer('total_cost')->default(0);
            $table->longText('purpose')->nullable();
            $table->longText('donor_requirements')->nullable();
            $table->longText('import_restrictions')->nullable();
            $table->enum('delivery', DeliveryMethod::values())->default(DeliveryMethod::DEFAULT);
            $table->longText('address')->nullable();
            $table->foreignId('approved_by')->nullable()->references('id')->on('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_requests');
    }
};
