<?php

namespace App\Models;

use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    use HasFactory;

    public static function getForm(): array
    {
        return [
            TextInput::make('name')
                ->required(),
        ];
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
