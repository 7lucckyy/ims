<?php

namespace App\Filament\Resources\IndicatorResource\Pages;

use App\Filament\Resources\IndicatorResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateIndicator extends CreateRecord
{
    protected static string $resource = IndicatorResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        // dd($data);

        $indicator = static::getModel()::create([
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

        return $indicator;
    }
}
