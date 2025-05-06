<?php

namespace App\Models;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;

class Vendor extends Model
{
    use HasFactory;

    public static function getForm(): array
    {
        return [
            Select::make('category_id')
                ->options(Category::all()->pluck('name', 'id'))
                ->label('Category')
                ->searchable()
                ->preload(),
            TextInput::make('name')
                ->required()
                ->maxLength(255),
            TextInput::make('email')
                ->email()
                ->maxLength(255),
            PhoneInput::make('phone')
                ->defaultCountry('NG')
                ->displayNumberFormat(PhoneInputNumberType::INTERNATIONAL)
                ->focusNumberFormat(PhoneInputNumberType::INTERNATIONAL),
            Textarea::make('address')
                ->columnSpanFull(),
            TextInput::make('location')
                ->maxLength(255),
            TextInput::make('recommendation')
                ->maxLength(255),
            TextInput::make('remarks')
                ->maxLength(255),
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }
}
