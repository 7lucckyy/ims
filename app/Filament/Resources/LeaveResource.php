<?php

namespace App\Filament\Resources;

use App\Enums\LeaveStatus;
use App\Filament\Resources\LeaveResource\Pages;
use App\Models\Leave;
use Carbon\Carbon;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class LeaveResource extends Resource
{
    protected static ?string $model = Leave::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'HR';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Leave::getForm());
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('user.name')
                    ->label('Staff')
                    ->weight(FontWeight::Bold)
                    ->icon('heroicon-o-user'),
                TextEntry::make('approval')
                    ->label('Status')
                    ->badge(),
                TextEntry::make('reason')
                    ->html()
                    ->columnSpanFull(),
                TextEntry::make('duration')
                    ->getStateUsing(fn ($record) => Carbon::parse($record->start_date)->toDateString().' to '.Carbon::parse($record->start_date)->toDateString()),
                TextEntry::make('document.file')
                    ->label('Document')
                    ->formatStateUsing(fn () => 'Download Document')
                    ->url(fn ($record) => Storage::url($record->document?->file), true)
                    ->badge()
                    ->color('blue'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Staff')
                    ->description(fn ($record) => 'Department: '.$record->user->staffDetail->department->name)
                    ->searchable(),
                Tables\Columns\TextColumn::make('period_of_leave')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('aspected_resumption_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('approval')
                    ->label('Status')
                    ->sortable(),
            ])
            ->query(function () {
                return Leave::latest();
            })
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                ActionGroup::make([
                    Action::make('approve')
                        ->icon(LeaveStatus::Approved->getIcon())
                        ->color(LeaveStatus::Approved->getColor())
                        ->hidden(fn ($record) => $record->approval->value === LeaveStatus::Approved->value)
                        ->requiresConfirmation()
                        ->action(fn ($record) => $record->approve()),
                    Action::make('reject')
                        ->icon(LeaveStatus::Rejected->getIcon())
                        ->color(LeaveStatus::Rejected->getColor())
                        ->hidden(fn ($record) => $record->approval->value === LeaveStatus::Rejected->value)
                        ->requiresConfirmation()
                        ->action(fn ($record) => $record->reject()),

                ]),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeaves::route('/'),
            'create' => Pages\CreateLeave::route('/create'),
            'edit' => Pages\EditLeave::route('/{record}/edit'),
            'view' => Pages\ViewLeave::route('/{record}/view'),
        ];
    }
}
