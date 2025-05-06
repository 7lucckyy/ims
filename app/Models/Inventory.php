<?php

namespace App\Models;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Hash;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Inventory extends Model
{
    use HasFactory;
    use HasSlug;

    public static function getForm(): array
    {
        return [
            Fieldset::make('Inventory Details')
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('batch_no')
                        ->required()
                        ->maxLength(255),
                    RichEditor::make('description')
                        ->columnSpanFull(),
                    TextInput::make('item_code')
                        ->label('Code')
                        ->maxLength(255),
                    TextInput::make('donation_cert_no')
                        ->label('Donation Certificate Number')
                        ->maxLength(255),
                    DatePicker::make('expiry_date'),
                    TextInput::make('total_quantity')
                        ->numeric(),
                ])
                ->columnSpan(8),
            Fieldset::make('Department & Approval')
                ->schema([
                    Select::make('department_id')
                        ->relationship('department', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Select::make('donor_id')
                        ->relationship('donor', 'name')
                        ->options(app('donors')->pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->createOptionForm(Donor::getForm())
                        ->createOptionModalHeading('Add Donor')
                        ->createOptionUsing(function (array $data): int {

                            $donor = User::create([
                                'name' => $data['name'],
                                'email' => $data['email'],
                                'password' => Hash::make('password'),
                            ]);

                            $donor->assignRole(Role::DONOR);

                            return $donor->getKey();
                        })
                        ->required(),
                    Select::make('approved_by')
                        ->required()
                        ->relationship('approvedBy', 'name')
                        ->options(app('staff')->pluck('name', 'id'))
                        ->searchable()
                        ->preload(),
                ])
                ->columns(1)
                ->columnSpan(4),
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function donor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'donor_id');
    }
}
