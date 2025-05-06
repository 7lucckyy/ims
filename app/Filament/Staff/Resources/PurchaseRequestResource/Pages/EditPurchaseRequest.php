<?php

namespace App\Filament\Staff\Resources\PurchaseRequestResource\Pages;

use App\Filament\Staff\Resources\PurchaseRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPurchaseRequest extends EditRecord
{
    protected static string $resource = PurchaseRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
