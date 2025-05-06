<?php

namespace App\Filament\Finance\Resources\ExpenseResource\Pages;

use App\Models\Expense;
use App\Models\ExpenseItem;
use App\Models\BudgetDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Finance\Resources\ExpenseResource;

class CreateExpense extends CreateRecord
{
    protected static string $resource = ExpenseResource::class;
    // Override the handleRecordCreation method
    protected function handleRecordCreation(array $data): Expense
    {
        return DB::transaction(function () use ($data) {
            // Create the expense record first (without the items)
            $expense = Expense::create([

                'budget_details_id' => $data['budget_details_id'],
                'vendor_id' => $data['vendor_id'],
                'budget_trench_id' => $data['budget_trench_id'],
                'ref_number' => $data['ref_number'],
                'transaction_date' => $data['transaction_date'],
                'memo' => $data['memo'],
                'category_id' => $data['items'][0]['category_id'],
                'attachment' => $data['attachment'],
                'total_amount' => collect($data['items'])->sum('amount'), // Calculate the total amount from items
            ]);

            // Save expense items and associate them with the created expense
            foreach ($data['items'] as $item) {
                ExpenseItem::create([
                    'expense_id' => $expense->id, // Associate with the expense
                    'category_id' => $item['category_id'],
                    'description' => $item['description'],
                    'amount' => $item['amount'],
                ]);
            }

            $totalAmount = collect($data['items'])->sum('amount'); // Get the total expense amount

            $budgetDetail = BudgetDetail::find($data['budget_details_id']);

            if ($budgetDetail) {
                $budgetDetail->remaining_amount = ($budgetDetail->remaining_amount == 0)
                    ? $budgetDetail->amount - $totalAmount
                    : $budgetDetail->remaining_amount - $totalAmount;

                $budgetDetail->deducted_amount += $totalAmount;

                $budgetDetail->save(); // Save the updated budget detail
            }

            return $expense;
        });
    }
}
