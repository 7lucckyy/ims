<?php

namespace App\Models;

use App\Enums\AgeBracket;
use App\Enums\DocumentType;
use App\Enums\Months;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Report extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'month' => Months::class,
            'age_bracket' => AgeBracket::class,
            'reach' => 'json',
        ];
    }

    public static function getForm(): array
    {
        return [
            Wizard::make([
                Wizard\Step::make('Report Details')
                    ->columns(2)
                    ->schema([
                        Select::make('month')
                            ->default(Months::DEFAULT)
                            ->enum(Months::class)
                            ->options(Months::class)
                            ->searchable()
                            ->required(),
                        Select::make('project_id')
                            ->relationship('project', 'name')
                            ->live()
                            ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->code} - {$record->name}")
                            ->createOptionForm(Project::getForm())
                            ->createOptionModalHeading('Add Project')
                            ->createOptionUsing(function (array $data): int {
                                $project = Project::create([
                                    'code' => $data['code'],
                                    'name' => $data['name'],
                                    'duration' => $data['duration'],
                                    'budget' => $data['budget'],
                                    'currency_id' => $data['currency_id'],
                                ]);

                                collect($data['locations'])->each(function ($location) use ($project) {

                                    $project->locations()->create([
                                        'name' => $location['name'],
                                        'location' => $location['location'],
                                    ]);

                                });

                                empty($data['budget_file']) ?: $project->documents()->create([
                                    'file' => $data['budget_file'],
                                    'type' => DocumentType::Budget,
                                ]);

                                collect($data['attachments'])->each(function ($attachment) use ($project) {

                                    $project->documents()->create([
                                        'file' => $attachment,
                                        'type' => DocumentType::Attachment,
                                    ]);

                                });

                                return $project->getKey();

                            })
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('location_id')
                            ->visible(fn (Get $get) => $get('project_id'))
                            ->options(fn (Get $get) => Location::query()
                                ->where('project_id', $get('project_id'))
                                ->pluck('name', 'id')
                                ->toArray()
                            )
                            ->searchable()
                            ->preload()
                            ->label('Location')
                            ->live()
                            ->required(),
                        Select::make('indicator_id')
                            ->relationship('indicator', 'name')
                            ->visible(fn (Get $get) => $get('project_id'))
                            ->options(fn (get $get) => Indicator::query()
                                ->where('project_id', $get('project_id'))
                                ->whereNotIn('id', Report::all()->pluck('indicator_id'))
                                ->pluck('name', 'id')
                                ->toArray()
                            )
                            ->createOptionForm(Indicator::getForm())
                            ->createOptionModalHeading('Add Indicator')
                            ->createOptionUsing(function (array $data): int {

                                $indicator = Indicator::create([
                                    'project_id' => $data['project_id'],
                                    'code' => $data['code'],
                                    'name' => $data['name'],
                                    'target' => $data['target'],
                                ]);

                                collect($data['meansOfMeasure'])->each(function ($item) use ($indicator) {

                                    $indicator->meansOfMeasure()->create([
                                        'name' => $item['name'],
                                        'value' => $item['value'],
                                    ]);

                                });

                                return $indicator->getKey();
                            })
                            ->editOptionForm(Indicator::getForm())
                            ->updateOptionUsing(function (array $data, $state) {

                                $indicator = Indicator::findOrFail($state);

                                $indicator->update([
                                    'project_id' => $data['project_id'],
                                    'code' => $data['code'],
                                    'name' => $data['name'],
                                    'target' => $data['target'],
                                ]);

                                $indicator->meansOfMeasure()->delete();

                                collect($data['meansOfMeasure'])->each(function ($item) use ($indicator) {

                                    $indicator->meansOfMeasure()->create([
                                        'name' => $item['name'],
                                        'value' => $item['value'],
                                    ]);

                                });

                                return $indicator;

                            })
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function ($state, Set $set) {

                                $indicator = Indicator::find($state);

                                if ($indicator) {
                                    $reachData = collect($indicator->meansOfMeasure)->map(function ($means) {
                                        return [
                                            'name' => $means->name ?? '',
                                            'value' => $means->value ?? '',
                                        ];
                                    })->toArray();

                                    $set('reach', $reachData);
                                }

                            })
                            ->required(),
                        RichEditor::make('narration')
                            ->required()
                            ->columnSpanFull(),
                    ]),
                Wizard\Step::make('Reach & Documents')
                    ->schema([
                        Fieldset::make('Reach')
                            ->schema([
                                Repeater::make('reach')
                                    ->columnSpanFull()
                                    ->columns(3)
                                    ->hiddenLabel()
                                    ->schema([
                                        TextInput::make('name')
                                            ->required(),
                                        TextInput::make('value')
                                            ->label('Target')
                                            ->numeric()
                                            ->readOnly()
                                            ->required(),
                                        TextInput::make('reach')
                                            ->numeric()
                                            ->required(),
                                    ])
                                    ->addable(false),
                            ]),
                        FileUpload::make('documents')
                            ->label('Support Documents')
                            ->multiple()
                            ->acceptedFileTypes(['pdf'])
                            ->hint('PDF files only'),
                    ]),
            ])
                ->columnSpanFull()
                ->skippable(),
        ];
    }

    public function indicator(): BelongsTo
    {
        return $this->belongsTo(Indicator::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'modelable');
    }
}
