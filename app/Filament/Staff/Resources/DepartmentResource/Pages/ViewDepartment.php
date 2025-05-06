<?php

namespace App\Filament\Staff\Resources\DepartmentResource\Pages;

use App\Filament\Staff\Resources\DepartmentResource;
use App\Models\PurchaseRequest;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Resources\Pages\ViewRecord;

class ViewDepartment extends ViewRecord
{
    protected static string $resource = DepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                Actions\EditAction::make(),
                Action::make('pr')
                    ->color('success')
                    ->icon('heroicon-o-banknotes')
                    ->label('Purchase Request')
                    ->form(PurchaseRequest::getForm())
                    ->action(function (array $data, $record) {

                        $record->purchaseRequests()->create([
                            'requested_by' => $record->hod_id,
                            'currency_id' => $data['currency_id'],
                            'vendor_id' => $data['vendor_id'],
                            'request_date' => $data['request_date'],
                            'items' => $data['items'],
                            'total_cost' => $data['total_cost'],
                            'notes' => $data['notes'],
                        ]);

                    }),
            ]),
        ];
    }
}
