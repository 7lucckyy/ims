<?php

namespace App\Models;

use App\Enums\DocumentType;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Indicator extends Model
{
    use HasFactory;

    public static function getForm(): array
    {
        return [
            Select::make('project_id')
                ->relationship('project', 'name')
                ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->code} - {$record->name}")
                ->searchable(['code', 'name'])
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
                ->preload()
                ->required(),
            TextInput::make('code')
                ->required(),
            TextInput::make('name')
                ->required(),
            TextInput::make('target')
                ->readOnly()
                ->required(),
            Fieldset::make('Means of Measure')
                ->schema([
                    Repeater::make('meansOfMeasure')
                        ->addActionLabel('Add Means of Measure')
                        ->columns(2)
                        ->columnSpanFull()
                        ->hiddenLabel()
                        ->live()
                        ->schema([
                            TextInput::make('name')
                                ->required(),
                            TextInput::make('value')
                                ->numeric()
                                ->required()
                                ->default(0),
                        ])
                        ->afterStateUpdated(function (Get $get, Set $set) {
                            self::totalTarget($get, $set);
                        }),
                ]),
        ];
    }

    public static function totalTarget(Get $get, Set $set): void
    {
        $items = collect($get('meansOfMeasure'));

        $total = 0;

        foreach ($items as $item) {
            $total += $item['value'];
        }

        $set('target', $total);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function meansOfMeasure(): HasMany
    {
        return $this->hasMany(MeansOfMeasure::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }
}
