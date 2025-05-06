<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Enums\DocumentType;
use App\Filament\Resources\ProjectResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateProject extends CreateRecord
{
    protected static string $resource = ProjectResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $project = static::getModel()::create([
            'code' => $data['code'],
            'name' => $data['name'],
            'budget_code' => $data['budget_code'],
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

        return $project;
    }
}
