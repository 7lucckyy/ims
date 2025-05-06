<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinanceReport extends Model
{
    public function budgetDetail()
    {
        return $this->belongsTo(BudgetDetail::class);
    }

    public function budgetTrench()
    {
        return $this->belongsTo(BudgetTrench::class);
    }

    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }

    public function expenseItem()
    {
        return $this->belongsTo(ExpenseItem::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    
}
