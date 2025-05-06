<?php

namespace App\Models;

use App\Enums\DeliveryMethod;
use App\Enums\Packaging;
use App\Enums\PRPriority;
use App\Enums\PurchaseStatus;
use App\Jobs\PRPdf;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Support\RawJs;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;

class PurchaseRequest extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'status' => PurchaseStatus::class,
            'priority' => PRPriority::class,
            'items' => 'json',
            'form' => Packaging::class,
            'delivery' => DeliveryMethod::class,
        ];
    }

    protected static function booted()
    {
        static::created(function(PurchaseRequest $purchaseRequest) {
            PRPdf::dispatch($purchaseRequest);
        });
    }

    public static function getForm(): array
    {
        return [
            Wizard::make([
                Wizard\Step::make('Primary Info')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('pr_number')
                                    ->label('PR No.')
                                    ->required(),
                                Select::make('currency_id')
                                    ->label('Currency')
                                    ->options(Currency::all()->pluck('abbr', 'id'))
                                    ->live()
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                            ]),
                        Grid::make(3)
                            ->schema([
                                TextInput::make('procurement_threshold')
                                    ->numeric()
                                    ->visible(fn (Get $get) => $get('currency_id'))
                                    ->prefix(fn (Get $get) => Currency::find($get('currency_id'))->abbr)
                                    // ->mask(RawJs::make('$money($input)'))
                                    ->required(),
                                TextInput::make('sole_quotation')
                                    ->numeric()
                                    ->visible(fn (Get $get) => $get('currency_id'))
                                    ->prefix(fn (Get $get) => Currency::find($get('currency_id'))->abbr)
                                    // ->mask(RawJs::make('$money($input)'))
                                    ->required(),
                                TextInput::make('negotiated_procedures')
                                    ->numeric()
                                    ->visible(fn (Get $get) => $get('currency_id'))
                                    ->prefix(fn (Get $get) => Currency::find($get('currency_id'))->abbr)
                                    // ->mask(RawJs::make('$money($input)'))
                                    ->required(),
                            ]),
                        Grid::make(2)
                            ->schema([
                                Select::make('state_id')
                                    ->options(State::all()->pluck('name', 'id'))
                                    ->label('State')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                Select::make('priority')
                                    ->default(PRPriority::DEFAULT)
                                    ->enum(PRPriority::class)
                                    ->options(PRPriority::class)
                                    ->searchable()
                                    ->required(),
                            ]),
                        Grid::make(2)
                            ->schema([
                                TextInput::make('office')
                                    ->required(),
                                Select::make('project_id')
                                    ->relationship('project', 'name')
                                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->code} - {$record->name}")
                                    ->searchable(['name', 'code'])
                                    ->preload()
                                    ->required(),
                            ]),
                        Grid::make(2)
                            ->schema([
                                Select::make('delivery')
                                    ->default(DeliveryMethod::DEFAULT)
                                    ->enum(DeliveryMethod::class)
                                    ->options(DeliveryMethod::class)
                                    ->searchable()
                                    ->required(),
                            ]),
                        Fieldset::make('Timelines')
                            ->schema([
                                DatePicker::make('request_date')
                                    ->label('Date request prepared')
                                    ->required(),
                                DatePicker::make('required_date')
                                    ->label('Date goods are required')
                                    ->required(),
                                DatePicker::make('end_date')
                                    ->label('Project end date')
                                    ->required(),
                            ]),
                        Grid::make(2)
                            ->schema([
                                Select::make('department_id')
                                    ->label('Department')
                                    ->options(fn () => Filament::getCurrentPanel()->getId() == 'staff' ? Department::where('hod_id', Auth::id())->get()->pluck('name', 'id') : Department::all()->pluck('name', 'id'))
                                    ->hidden(fn () => Filament::getCurrentPanel()->getId() == 'staff')
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->required(),
                                Select::make('requested_by')
                                    ->label('Requested By')
                                    ->visible(fn (Get $get) => $get('department_id'))
                                    ->hidden(fn () => Filament::getCurrentPanel()->getId() == 'staff')
                                    ->options(fn (Get $get) => Department::find($get('department_id'))->staff()->get()->pluck('user.name', 'user.id'))
                                    ->reactive()
                                    ->hidden(fn () => Filament::getCurrentPanel()->getId() == 'staff')
                                    ->searchable()
                                    ->preload(),
                            ]),
                    ]),
                Wizard\Step::make('Items')
                    ->schema([
                        Fieldset::make('Items')
                            ->schema([
                                Repeater::make('items')
                                    ->addActionLabel('Add Item')
                                    ->hiddenLabel()
                                    ->cloneable()
                                    ->columns(7)
                                    ->columnSpanFull()
                                    ->live()
                                    ->schema([
                                        TextInput::make('name')
                                            ->required(),
                                        Textarea::make('description'),
                                        Select::make('form')
                                            ->default(Packaging::DEFAULT)
                                            ->enum(Packaging::class)
                                            ->options(Packaging::class)
                                            ->searchable()
                                            ->required(),
                                        TextInput::make('frequency')
                                            ->numeric()
                                            ->required()
                                            ->reactive()
                                            ->afterStateUpdated(fn ($set, $get) =>
                                                $set('amount', $get('unit_cost') * $get('quantity') * $get('frequency'))
                                            ),
                                        TextInput::make('quantity')
                                            ->numeric()
                                            ->required()
                                            ->reactive()
                                            ->afterStateUpdated(fn ($set, $get) =>
                                                $set('amount', $get('unit_cost') * $get('quantity') * $get('frequency'))
                                            ),
                                        TextInput::make('unit_cost')
                                            ->numeric()
                                            ->required()
                                            ->reactive()
                                            ->afterStateUpdated(fn ($set, $get) =>
                                                $set('amount', $get('unit_cost') * $get('quantity') * $get('frequency'))
                                            ),
                                        TextInput::make('amount')
                                            ->required()
                                            ->numeric()
                                            ->readOnly(),
                                    ])
                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                        self::totals($get, $set);
                                    }),
                            ]),
                        TextInput::make('total_cost')
                            ->columnSpanFull()
                            ->required()
                            ->readOnly(),
                    ]),
                Wizard\Step::make('Requirements')
                    ->schema([
                        RichEditor::make('purpose')
                            ->label('Purpose of Request')
                            ->columnSpanFull(),
                        RichEditor::make('donor_requirements')
                            ->label('Any donor requirements exceeding GPON Procurement Policy')
                            ->columnSpanFull(),
                        RichEditor::make('import_restrictions')
                            ->label('Any import restrictions or limitations on transport of goods(if known)')
                            ->columnSpanFull(),
                        RichEditor::make('address')
                            ->columnSpanFull(),
                    ]),
            ])
                ->columnSpanFull()
                ->skippable(),
        ];
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function purchaseOrder(): HasOne
    {
        return $this->hasOne(PurchaseOrder::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public static function totals(Get $get, Set $set): void
    {
        $total = 0;

        foreach (collect($get('items')) as $item) {
            $total += $item['quantity'] * $item['unit_cost'] * $item['frequency'];
        }

        $set('total_cost', $total);
    }

    public function approve(): void
    {
        $this->update([
            'status' => PurchaseStatus::Approved,
            'approved_by' => Auth::user()->id,
        ]);
    }

    public function reject(): void
    {
        $this->update([
            'status' => PurchaseStatus::Rejected,
        ]);
    }
}
