<?php

namespace App\Models;

use App\Enums\BidStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompetitiveBid extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'status' => BidStatus::class,
        ];
    }

    public static function getForm(): array
    {
        return [
            Select::make('currency_id')
                ->relationship('currency', 'name')
                ->live()
                ->required()
                ->searchable()
                ->preload(),
            Select::make('project_id')
                ->relationship('project', 'name')
                ->required()
                ->searchable()
                ->preload(),
            Select::make('vendor_id')
                ->relationship('vendor', 'name')
                ->required()
                ->searchable()
                ->preload(),
            Select::make('status')
                ->default(BidStatus::DEFAULT)
                ->enum(BidStatus::class)
                ->options(BidStatus::class)
                ->searchable()
                ->required(),
            Grid::make(2)
                ->visible(fn (Get $get) => $get('currency_id'))
                ->schema([
                    TextInput::make('bid_amount')
                        ->required()
                        ->prefix(fn (Get $get) => Currency::find($get('currency_id'))->abbr)
                        ->numeric()
                        ->live(),
                    TextInput::make('our_bid_amount')
                        ->required()
                        ->prefix(fn (Get $get) => Currency::find($get('currency_id'))->abbr)
                        ->numeric()
                        ->live()
                        ->afterStateUpdated(function ($state, Set $set, $get) {
                            if ($state && $get('bid_amount')) {
                                $variance = $get('bid_amount') - $state;
                                $percentage = ($variance / $state) * 100;

                                $set('variance_amount', $variance);
                                $set('variance_percentage', number_format($percentage, 2));
                            }
                        }),
                ]),
            Grid::make(2)
                ->visible(fn (Get $get) => $get('currency_id'))
                ->schema([
                    TextInput::make('variance_amount')
                        ->prefix(fn (Get $get) => Currency::find($get('currency_id'))->abbr)
                        ->disabled()
                        ->numeric(),
                    TextInput::make('variance_percentage')
                        ->disabled()
                        ->suffix('%'),
                ]),
            DatePicker::make('bid_date')
                ->required(),
            RichEditor::make('notes')
                ->columnSpanFull(),
        ];
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }
}
