<?php

namespace App\Filament\Hr\Resources\AppraisalResource\Pages;

use App\Filament\Resources\AppraisalResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAppraisal extends ViewRecord
{
    protected static string $resource = AppraisalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
