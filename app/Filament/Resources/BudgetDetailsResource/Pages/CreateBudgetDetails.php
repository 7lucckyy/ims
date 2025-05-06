<?php

namespace App\Filament\Resources\BudgetDetailsResource\Pages;

use App\Filament\Resources\BudgetDetailsResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

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
