<?php

namespace App\Models;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Position extends Model
{
    use HasFactory;

    public static function getForm(): array
    {
        return [
            Select::make('department_id')
                ->options(Department::all()->pluck('name', 'id'))
                ->label('Department')
                ->createOptionForm(Department::getForm())
                ->createOptionModalHeading('Add Department')
                ->searchable()
                ->preload()
                ->required(),
            TextInput::make('name')
                ->required(),
        ];
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
