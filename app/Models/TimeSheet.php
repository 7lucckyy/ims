<?php

namespace App\Models;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeSheet extends Model
{
    use HasFactory;

    public static function getForm(): array
    {
        return [
            Select::make('user_id')
                ->options(User::role(Role::STAFF)->get()->pluck('name', 'id'))
                ->searchable()
                ->preload()
                ->label('Staff')
                ->required(),
            TextInput::make('hours')
                ->required()
                ->numeric(),
            DatePicker::make('date')
                ->required(),
            RichEditor::make('description')
                ->columnSpanFull(),
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
