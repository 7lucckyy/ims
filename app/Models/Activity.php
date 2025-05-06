<?php

namespace App\Models;

use App\Enums\DocumentType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends Model
{
    use HasFactory;

    public static function getForm(): array
    {
        return [
            Select::make('project_id')
                ->live()
                ->relationship('project', 'name')
                ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->code} - {$record->name}")
                ->searchable(['code', 'name'])
                ->preload()
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
                ->required(),
            Select::make('indicator_id')
                ->relationship('indicator', 'name')
                ->options(fn (Get $get) => Indicator::where('project_id', $get('project_id'))->pluck('name', 'id')->toArray())
                ->searchable(['code', 'name'])
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

                    return $indicator->getKey();

                })
                ->preload()
                ->live()
                ->required(),
            TextInput::make('name')
                ->required(),
            TextInput::make('code')
                ->required(),
        ];
    }

    public function indicator(): BelongsTo
    {
        return $this->belongsTo(Indicator::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
