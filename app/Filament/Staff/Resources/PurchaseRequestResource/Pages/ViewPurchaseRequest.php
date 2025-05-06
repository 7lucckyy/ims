<?php

namespace App\Filament\Staff\Resources\PurchaseRequestResource\Pages;

use App\Enums\PurchaseStatus;
use App\Filament\Staff\Resources\PurchaseRequestResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\URL;

class ViewPurchaseRequest extends ViewRecord
{
    protected static string $resource = PurchaseRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                Actions\EditAction::make(),
                Action::make('approve')
                    ->visible(fn ($record) => ! $record->purchaseOrder)
                    ->icon(PurchaseStatus::Approved->getIcon())
                    ->color(PurchaseStatus::Approved->getColor())
                    ->requiresConfirmation()
                    ->action(function ($record) {

                        $record->approve();

                        $record->purchaseOrder()->create([
                            'total' => $record->total_cost,
                            'items' => $record->items,
                            'currency_id' => $record->currency_id,
                            'vendor_id' => $record->vendor_id,
                            'department_id' => $record->department_id,
                            'notes' => $record->notes,
                        ]);

                    }),
                Action::make('reject')
                    ->visible(fn ($record) => $record->status->value === PurchaseStatus::Pending->value)
                    ->icon(PurchaseStatus::Rejected->getIcon())
                    ->color(PurchaseStatus::Rejected->getColor())
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->reject()),
                Action::make('pdf')
                    ->label('Download PDF')
                    ->color('blue')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function($record) {

                        return response()->download(storage_path('app/public/purchaseRequests/') . $record->filename);

                    }),
            ]),
        ];
    }
}
