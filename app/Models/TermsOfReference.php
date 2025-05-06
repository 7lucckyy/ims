<?php

namespace App\Models;

use App\Enums\BudgetTrenchStatus;
use App\Jobs\TORPdf;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TermsOfReference extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'budget' => 'json',
        ];
    }

    protected static function booted()
    {
        static::created(function(TermsOfReference $termsOfReference) {
            TORPdf::dispatch($termsOfReference);
        });
    }

    public static function getForm(): array
    {
        return [
            Wizard::make([
                Wizard\Step::make('Project & Budget')
                    ->schema([
                        Select::make('project_id')
                            ->relationship('project', 'name')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required(),
                        Grid::make(2)
                            ->schema([
                                Select::make('location_id')
                                    ->visible(fn (Get $get) => $get('project_id'))
                                    ->relationship('location', 'name')
                                    ->options(fn (Get $get) => Location::query()
                                        ->where('project_id', $get('project_id'))
                                        ->pluck('name', 'id')
                                        ->toArray()
                                    )
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                Select::make('budget_trench_id')
                                    ->visible(fn (Get $get) => $get('project_id'))
                                    ->relationship('budgetTrench', 'id')
                                    ->options(fn (Get $get) => BudgetTrench::query()
                                        ->where('project_id', $get('project_id'))
                                        ->pluck('code', 'id')
                                        ->toArray()
                                    )
                                    ->live()
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        TextInput::make('code')
                                            ->required(),
                                        TextInput::make('amount')
                                            ->required(),
                                        Select::make(name: 'status')
                                            ->default(BudgetTrenchStatus::DEFAULT)
                                            ->enum(BudgetTrenchStatus::class)
                                            ->options(BudgetTrenchStatus::class)
                                            ->searchable()
                                            ->required(),
                                        DatePicker::make('transaction_date')
                                            ->required(),
                                        DatePicker::make('start_date')
                                            ->required(),
                                        DatePicker::make('end_date')
                                            ->required(),
                                    ])
                                    ->createOptionModalHeading('Add Budget Trench')
                                    ->createOptionUsing(function (array $data, Get $get): int {
                                        $project = Project::find($get('project_id'));

                                        $budgetTrench = $project->budgetTrenches()->create($data);

                                        return $budgetTrench->getKey();
                                    })
                                    ->required(),
                            ]),
                        Grid::make(2)
                            ->schema([
                                TextInput::make('duty_station')
                                    ->required()
                                    ->maxLength(255),
                                Select::make('budget_holder')
                                    ->options(function() {

                                        $hodIds = Department::all()->pluck('hod_id');

                                        return User::whereIn('id', $hodIds)->pluck('name', 'id');

                                    })
                                    ->hidden(fn () => Filament::getCurrentPanel()->getId() == 'staff')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                            ]),
                        Fieldset::make('Budget')
                            ->visible(fn(Get $get) => $get('budget_trench_id'))
                            ->schema([
                                Repeater::make('budget')
                                    ->addActionLabel('Add Budget Item')
                                    ->cloneable()
                                    ->columns(6)
                                    ->columnSpanFull()
                                    ->hiddenLabel()
                                    ->live()
                                    ->schema([
                                        Select::make('budget_line')
                                            ->options(fn(Get $get) => BudgetDetail::where('budget_trench_id', $get('../../budget_trench_id'))->pluck('line', 'line'))
                                            ->searchable()
                                            ->preload(),
                                        Textarea::make('description')
                                            ->required(),
                                        TextInput::make('unit_cost')
                                            ->required()
                                            ->numeric()
                                            ->reactive()
                                            ->afterStateUpdated(fn ($set, $get) =>
                                                $set('amount', $get('unit_cost') * $get('quantity') * $get('frequency'))
                                            ),
                                        TextInput::make('quantity')
                                            ->required()
                                            ->numeric()
                                            ->reactive()
                                            ->afterStateUpdated(fn ($set, $get) =>
                                                $set('amount', $get('unit_cost') * $get('quantity') * $get('frequency'))
                                            ),
                                        TextInput::make('frequency')
                                            ->required()
                                            ->numeric()
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
                                TextInput::make('total')
                                    ->readOnly(),
                            ]),
                    ]),
                Wizard\Step::make('Particulars')
                    ->schema([
                        RichEditor::make('background')
                            ->required()
                            ->columnSpanFull(),
                        RichEditor::make('justification')
                            ->required()
                            ->columnSpanFull(),
                        RichEditor::make('project_output')
                            ->required()
                            ->columnSpanFull(),
                        RichEditor::make('activity_objectives')
                            ->required()
                            ->columnSpanFull(),
                        RichEditor::make('activity_expected_output')
                            ->required()
                            ->columnSpanFull(),
                        RichEditor::make('micro_activities')
                            ->required()
                            ->columnSpanFull(),
                        RichEditor::make('modalities_of_implementation')
                            ->required()
                            ->columnSpanFull(),
                        Select::make('prepared_by')
                            ->options(app('staff')->pluck('name', 'id'))
                            ->hidden(fn () => Filament::getCurrentPanel()->getId() == 'staff')
                            ->searchable()
                            ->preload()
                            ->required(),
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

    public function authorizedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'authorized_by');
    }

    public function budgetHolder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'budget_holder');
    }

    public function budgetTrench(): BelongsTo
    {
        return $this->belongsTo(BudgetTrench::class);
    }

    public function confirmedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function preparedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'prepared_by');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public static function totals(Get $get, Set $set): void
    {
        $total = 0;

        foreach (collect($get('budget')) as $item) {
            $aggregate = $item['quantity'] * $item['unit_cost'] * $item['frequency'];

            $total += $aggregate;
        }

        $set('total', $total);
    }
}
