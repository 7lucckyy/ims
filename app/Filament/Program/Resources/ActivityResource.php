<?php

namespace App\Filament\Program\Resources;

use App\Filament\Program\Resources\ActivityResource\Pages;
use App\Models\Activity;
use App\Models\Project;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ActivityResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?string $navigationIcon = 'heroicon-o-square-3-stack-3d';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationGroup = 'MEAL';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Activity::getForm());
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('name')
                    ->weight(FontWeight::Bold),
                TextEntry::make('code')
                    ->weight(FontWeight::Bold),
                TextEntry::make('project.name')
                    ->weight(FontWeight::Bold),
                TextEntry::make('indicator.name')
                    ->weight(FontWeight::Bold),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('project_id')
                    ->label('Project')
                    ->options(Project::all()->pluck('name', 'id')),
            ])
            ->actions([
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
            'index' => Pages\ListActivities::route('/'),
            'create' => Pages\CreateActivity::route('/create'),
            'edit' => Pages\EditActivity::route('/{record}/edit'),
            'view' => Pages\ViewActivity::route('/{record}'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'code', 'project.name', 'indicator.name'];
    }

    public static function getGlobalSearchResultUrl(Model $record): string
    {
        return ActivityResource::getUrl('view', ['record' => $record]);
    }
}
