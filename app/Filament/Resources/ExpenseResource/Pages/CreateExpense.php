<?php

namespace App\Filament\Resources\ExpenseResource\Pages;

use App\Filament\Resources\ExpenseResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateExpense extends CreateRecord
{
    protected static string $resource = ExpenseResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        unset($data['name']);

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $expense = static::getModel()::create([
            'code' => $data['code'],
            'name' => $data['name'],
            'budget_code' => $data['budget_code'],
            'duration' => $data['duration'],
            'budget' => $data['budget'],
            'currency_id' => $data['currency_id'],
        ]);

        collect($data['items'])->each(function ($items) use ($expense) {

            $expense->locations()->create([
                'name' => $items['name'],
                'location' => $items['location'],
            ]);

        });

        return $expense;
    }
}
