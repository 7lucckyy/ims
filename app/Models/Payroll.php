<?php

namespace App\Models;

use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payroll extends Model
{
    use HasFactory;

    public static function getForm(): array
    {
        return [
            Select::make('user_id')
                ->label('Staff')
                ->options(function () {
                    $inPayroll = Payroll::all()->pluck('user_id');

                    return User::role(Role::STAFF)
                        ->whereNotIn('id', $inPayroll)
                        ->pluck('name', 'id');
                })
                ->hiddenOn('edit')
                ->searchable()
                ->preload()
                ->required(),
            Select::make('currency_id')
                ->relationship('currency', 'abbr')
                ->searchable()
                ->preload()
                ->required(),
            Fieldset::make('Payment Parameters')
                ->schema([
                    TextInput::make('monthly_gross')
                        ->required()
                        ->numeric(),
                    TextInput::make('paye_tax')
                        ->suffix('%')
                        ->maxValue(100)
                        ->required()
                        ->numeric(),
                    TextInput::make('net_pay')
                        ->required()
                        ->numeric(),
                    TextInput::make('health_insurance')
                        ->numeric(),
                    TextInput::make('pension')
                        ->required()
                        ->numeric(),
                ]),
            Fieldset::make('Bank Details')
                ->schema([
                    TextInput::make('bank_name')
                        ->label('Name')
                        ->required(),
                    TextInput::make('bank_acc_no')
                        ->label('Account Number')
                        ->required(),
                ]),
        ];
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
