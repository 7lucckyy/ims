<?php

namespace App\Models;

use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donor extends Model
{
    use HasFactory;

    public static function getForm(): array
    {
        return [
            TextInput::make('name')
                ->required(),
            TextInput::make('email')
                ->email()
                ->unique('users', 'email')
                ->required()
                ->prefixIcon('heroicon-o-envelope'),
        ];
    }
}
