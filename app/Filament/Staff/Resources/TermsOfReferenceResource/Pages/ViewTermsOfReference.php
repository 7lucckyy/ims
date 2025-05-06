<?php

namespace App\Filament\Staff\Resources\TermsOfReferenceResource\Pages;

use App\Filament\Staff\Resources\TermsOfReferenceResource;
use App\Models\User;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\Select;
use Filament\Notifications\Actions\Action as ActionsAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;

class ViewTermsOfReference extends ViewRecord
{
    protected static string $resource = TermsOfReferenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                Actions\EditAction::make(),
                Action::make('confirm')
                    ->label('Confirm Budget')
                    ->visible(fn ($record) => !$record->confirmedBy && $record->request_confirmation == Auth::id())
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function ($record, array $data) {

                        $record->update(['confirmed_by' => Auth::id()]);

                    }),
                Action::make( 'request_confirmation')
                    ->label('Request Confirmation')
                    ->visible(fn($record) => !$record->request_confirmation && $record->prepared_by == Auth::id())
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->modalSubmitActionLabel('Request')
                    ->form([
                        Select::make('request_confirmation')
                            ->hiddenLabel()
                            ->options(app('staff')->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])
                    ->action(function($record, array $data) {

                        $record->update(['request_confirmation' => $data['request_confirmation']]);

                        $recipient = User::find($data['request_confirmation']);

                        $recipient->notify(

                            Notification::make()
                                ->title('TOR Budget Confirmation')
                                ->body('You have been requested to approve TOR budget')
                                ->icon('heroicon-o-banknotes')
                                ->iconColor('success')
                                ->actions([
                                    ActionsAction::make('view')
                                        ->color('info')
                                        ->icon('heroicon-o-eye')
                                        ->url(TermsOfReferenceResource::getUrl('view', [$record->id]))
                                        ->markAsRead()
                                ])
                                ->toDatabase()
                        );

                    }),
                Action::make(name: 'review')
                    ->label('Review TOR')
                    ->visible(fn ($record) => ! $record->reviewedBy && $record->request_review == Auth::id())
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->requiresConfirmation()
                    ->action(function ($record) {

                        $record->update(['reviewed_by' => Auth::id()]);

                    }),
                Action::make( 'request_review')
                    ->label('Request Review')
                    ->visible(fn($record) => !$record->request_review && $record->prepared_by == Auth::id())
                    ->icon('heroicon-o-exclamation-circle')
                    ->color('info')
                    ->modalSubmitActionLabel('Request')
                    ->form([
                        Select::make('request_review')
                            ->hiddenLabel()
                            ->options(app('staff')->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])
                    ->action(function($record, array $data) {

                        $record->update(['request_review' => $data['request_review']]);

                        $recipient = User::find($data['request_review']);

                        $recipient->notify(

                            Notification::make()
                                ->title('TOR Review')
                                ->body('You have been requested to review TOR')
                                ->icon('heroicon-o-exclamation-circle')
                                ->iconColor('info')
                                ->actions([
                                    ActionsAction::make('view')
                                        ->color('info')
                                        ->icon('heroicon-o-eye')
                                        ->url(TermsOfReferenceResource::getUrl('view', [$record->id]))
                                        ->markAsRead()
                                ])
                                ->toDatabase()
                        );

                    }),
                Action::make(name: 'approve')
                    ->label('Approve TOR')
                    ->visible(fn ($record) => ! $record->approvedBy && $record->request_review == Auth::id())
                    ->icon('heroicon-o-check')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->action(function ($record) {

                        $record->update(['approved_by' => Auth::id()]);

                    }),
                Action::make( 'request_approval')
                    ->label('Request Approval')
                    ->visible(fn($record) => !$record->request_approval && $record->prepared_by == Auth::id())
                    ->icon('heroicon-o-check')
                    ->color('warning')
                    ->modalSubmitActionLabel('Request')
                    ->form([
                        Select::make('request_approval')
                            ->hiddenLabel()
                            ->options(app('staff')->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])
                    ->action(function($record, array $data) {

                        $record->update(['request_approval' => $data['request_approval']]);

                        $recipient = User::find($data['request_approval']);

                        $recipient->notify(

                            Notification::make()
                                ->title('TOR Approval')
                                ->body('You have been requested to approve TOR')
                                ->icon('heroicon-o-check')
                                ->iconColor('warning')
                                ->actions([
                                    ActionsAction::make('view')
                                        ->color('warning')
                                        ->icon('heroicon-o-eye')
                                        ->url(TermsOfReferenceResource::getUrl('view', [$record->id]))
                                        ->markAsRead()
                                ])
                                ->toDatabase()
                        );

                    }),
                Action::make(name: 'authorize')
                    ->label('Authorize TOR')
                    ->visible(fn ($record) => ! $record->authorizedBy && $record->request_authorization == Auth::id())
                    ->icon('heroicon-o-check-badge')
                    ->color('fuchsia')
                    ->requiresConfirmation()
                    ->action(function ($record) {

                        $record->update(['authorized_by' => Auth::id()]);

                    }),
                Action::make( 'request_authorization')
                    ->label('Request Authorization')
                    ->visible(fn($record) => !$record->request_authorization && $record->prepared_by == Auth::id())
                    ->icon('heroicon-o-check-badge')
                    ->color('fuchsia')
                    ->modalSubmitActionLabel('Request')
                    ->form([
                        Select::make('request_authorization')
                            ->hiddenLabel()
                            ->options(app('staff')->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])
                    ->action(function($record, array $data) {

                        $record->update(['request_authorization' => $data['request_authorization']]);

                        $recipient = User::find($data['request_authorization']);

                        $recipient->notify(

                            Notification::make()
                                ->title('TOR Authorization')
                                ->body('You have been requested to authorize TOR')
                                ->icon('heroicon-o-check-badge')
                                ->iconColor('fuchsia')
                                ->actions([
                                    ActionsAction::make('view')
                                        ->color('fuchsia')
                                        ->icon('heroicon-o-eye')
                                        ->url(TermsOfReferenceResource::getUrl('view', [$record->id]))
                                        ->markAsRead()
                                ])
                                ->toDatabase()
                        );

                    }),
            ])

        ];
    }
}
