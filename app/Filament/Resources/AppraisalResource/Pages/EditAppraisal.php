<?php

namespace App\Filament\Resources\AppraisalResource\Pages;

use App\Filament\Resources\AppraisalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAppraisal extends EditRecord
{
    protected static string $resource = AppraisalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
