<?php

namespace App\Models;

use App\Enums\BudgetTrenchStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BudgetTrench extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'type' => BudgetTrenchStatus::class,
        ];
    }

    public static function getForm()
    {
        return [
            Select::make('project_id')
                ->live()
                ->relationship('project', 'budget_code')
                ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->budget_code} - {$record->name}")
                ->searchable(['budget_code'])
                ->preload()
                ->required(),
            TextInput::make('code')
                ->required(),
            TextInput::make('amount')
                ->numeric()
                ->required(),
            Select::make(name: 'status')
                ->default(BudgetTrenchStatus::DEFAULT)
                ->enum(BudgetTrenchStatus::class)
                ->options(BudgetTrenchStatus::class)
                ->searchable()
                ->required(),
            DatePicker::make('transaction_date')
                ->required(),
            DatePicker::make('start_date')
                ->required(),
            DatePicker::make('end_date')
                ->required(),
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function budget_details(): HasMany
    {
        return $this->hasMany(BudgetDetail::class);
    }
}
