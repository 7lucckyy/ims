<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Filament\Facades\Filament;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
            'items' => 'json',
        ];
    }

    public static function getForm(): array
    {
        return [
            Select::make('department_id')
                ->label('Department')
                ->options(fn () => Filament::getCurrentPanel()->getId() == 'staff' ? Department::where('hod_id', Auth::id())->get()->pluck('name', 'id') : Department::all()->pluck('name', 'id'))
                ->searchable()
                ->preload()
                ->live()
                ->required(),
            Select::make('status')
                ->default(OrderStatus::DEFAULT)
                ->enum(OrderStatus::class)
                ->options(OrderStatus::class)
                ->searchable()
                ->required(),
            Select::make('currency_id')
                ->label('Currency')
                ->options(Currency::all()->pluck('abbr', 'id'))
                ->searchable()
                ->required()
                ->preload(),
            Select::make('vendor_id')
                ->label('Vendor')
                ->options(Vendor::all()->pluck('name', 'id'))
                ->searchable()
                ->preload()
                ->required()
                ->createOptionForm(Vendor::getForm())
                ->createOptionModalHeading('Add Vendor')
                ->createOptionUsing(function (array $data): int {
                    $vendor = Vendor::create($data);

                    return $vendor->getKey();
                }),
            Fieldset::make('Items & Total')
                ->schema([
                    Repeater::make('items')
                        ->addActionLabel('Add Item')
                        ->columns(4)
                        ->columnSpanFull()
                        ->live()
                        ->schema([
                            TextInput::make('name')
                                ->required(),
                            Textarea::make('description'),
                            TextInput::make('quantity')
                                ->numeric()
                                ->required(),
                            TextInput::make('unit_cost')
                                ->numeric()
                                ->required(),
                        ])
                        ->afterStateUpdated(function (Get $get, Set $set) {
                            self::totals($get, $set);
                        }),
                    TextInput::make('total')
                        ->columnSpanFull()
                        ->required()
                        ->readOnly(),
                ]),
            RichEditor::make('notes')
                ->columnSpanFull(),
        ];
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function purchaseRequest(): BelongsTo
    {
        return $this->belongsTo(PurchaseRequest::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function approve(): void
    {
        $this->update(['status' => OrderStatus::Approved]);
    }

    public function ship(): void
    {
        $this->update(['status' => OrderStatus::Shipped]);
    }

    public function complete(): void
    {
        $this->update(['status' => OrderStatus::Completed]);
    }

    public static function totals(Get $get, Set $set): void
    {
        $total = 0;

        foreach (collect($get('items')) as $item) {
            $total += $item['quantity'] * $item['unit_cost'];
        }

        $set('total', $total);
    }
}
