<?php

namespace App\Filament\Meal\Resources\IndicatorResource\Pages;

use App\Filament\Meal\Resources\IndicatorResource;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;

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

        collect($data['meansOfMeasure'])->each(function($item) use($indicator) {

            $indicator->meansOfMeasure()->create([
                'name' => $item['name'],
                'value' => $item['value'],
            ]);

        });

        return $indicator;
    }
}
