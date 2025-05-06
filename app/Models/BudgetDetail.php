<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BudgetDetail extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'budget_trench_id', 'line', 'description', 'amount'];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function budgetTrench(): BelongsTo
    {
        return $this->belongsTo(BudgetTrench::class);
    }


}
