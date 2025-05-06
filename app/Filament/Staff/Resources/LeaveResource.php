<?php

namespace App\Filament\Staff\Resources;

use App\Filament\Staff\Resources\LeaveResource\Pages;
use App\Models\Leave;
use Carbon\Carbon;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LeaveResource extends Resource
{
    protected static ?string $model = Leave::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Leave::getForm());
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
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
                    ->url(fn ($record) => Storage::url($record->document->file), true)
                    ->badge()
                    ->color('blue'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
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
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'view' => Pages\ViewLeave::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('user_id', Auth::id())
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
