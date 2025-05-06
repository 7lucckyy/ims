<?php

namespace App\Filament\Finance\Resources\BudgetDetailsResource\Pages;

use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Finance\Resources\BudgetDetailsResource;

class CreateBudgetDetails extends CreateRecord
{
    protected static string $resource = BudgetDetailsResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        dd($data);
        // $budgetDetails = static::getModel()::create([
        //     'project_id' => $data['project_id'],
        //     'budget_trench_id' => $data['budget_trench_id'],
        //     'line' => $data['line'],
        //     'description' => $data['description'],
        //     'amount' => $data['amount'],
        // ]);

        // dd($budgetDetails);
    }
}
