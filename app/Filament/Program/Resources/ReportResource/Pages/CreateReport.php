<?php

namespace App\Filament\Program\Resources\ReportResource\Pages;

use App\Filament\Program\Resources\ReportResource;
use App\Enums\DocumentType;
use App\Models\Indicator;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateReport extends CreateRecord
{
    protected static string $resource = ReportResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $report = static::getModel()::create([
            'narration' => $data['narration'],
            'month' => $data['month'],
            'project_id' => $data['project_id'],
            'location_id' => $data['location_id'],
            'indicator_id' => $indicatorId = $data['indicator_id'],
            'reach' => $data['reach'],
        ]);

        collect($data['documents'])->each(function ($document) use ($report) {

            $report->documents()->create([
                'type' => DocumentType::Attachment,
                'file' => $document,
            ]);

        });

        $indicator = Indicator::find($indicatorId);

        $reach = 0;

        // dd($data['reach']);

        collect($data['reach'])->each(function ($element) use (&$reach) {

            $reached = (int) $element['reach'];

            $reach += $reached;
        });

        $indicator->update(['reach' => $reach]);

        return $report;
    }
}
