<?php

namespace App\Models;

use App\Models\Expense;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpenseItem extends Model
{
    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expense::class);
    }
}
