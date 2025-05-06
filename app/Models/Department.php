<?php

namespace App\Models;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    public static function getForm(): array
    {
        return [
            TextInput::make('name')
                ->columnSpanFull()
                ->required(),
            Select::make('hod_id')
                ->options(User::role(Role::STAFF)->get()->pluck('name', 'id'))
                ->columnSpanFull()
                ->label('Head of Department')
                ->searchable()
                ->preload(),
        ];
    }

    public function hod(): BelongsTo
    {
        return $this->belongsTo(User::class, 'hod_id');
    }

    public function inventories(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }

    public function leaves(): HasMany
    {
        return $this->hasMany(Leave::class);
    }

    public function positions(): HasMany
    {
        return $this->hasMany(Position::class);
    }

    public function purchaseRequests(): HasMany
    {
        return $this->hasMany(PurchaseRequest::class);
    }

    public function staff(): HasMany
    {
        return $this->hasMany(StaffDetail::class);
    }
}
