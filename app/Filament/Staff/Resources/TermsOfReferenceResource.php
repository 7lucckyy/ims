<?php

namespace App\Filament\Staff\Resources;

use App\Filament\Staff\Resources\TermsOfReferenceResource\Pages;
use App\Filament\Staff\Resources\TermsOfReferenceResource\RelationManagers;
use App\Models\TermsOfReference;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Number;

class TermsOfReferenceResource extends Resource
{
    protected static ?string $model = TermsOfReference::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(TermsOfReference::getForm());
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make([
                    TextEntry::make('project.name')
                        ->inlineLabel()
                        ->label('Project Name')
                        ->weight(FontWeight::Bold)
                        ->color('info'),
                    TextEntry::make('project.code')
                        ->inlineLabel()
                        ->label('Project Code')
                        ->weight(FontWeight::Bold)
                        ->color('info'),
                    TextEntry::make('duty_station')
                        ->inlineLabel()
                        ->weight(FontWeight::Bold)
                        ->color('info'),
                    TextEntry::make('budgetHolder.name')
                        ->inlineLabel()
                        ->label('Budget Holder')
                        ->weight(FontWeight::Bold)
                        ->color('info'),
                    TextEntry::make('location.name')
                        ->inlineLabel()
                        ->label('Location')
                        ->weight(FontWeight::Bold)
                        ->color('info'),
                    TextEntry::make('budgetTrench.code')
                        ->inlineLabel()
                        ->label('Budget Line')
                        ->weight(FontWeight::Bold)
                        ->color('info'),
                ])
                    ->columns(1),
                Tabs::make()
                    ->columnSpanFull()
                    ->tabs([
                        Tabs\Tab::make('Description')
                            ->schema([
                                TextEntry::make('background')
                                    ->inlineLabel()
                                    ->html()
                                    ->columnSpanFull(),
                                TextEntry::make('justification')
                                    ->inlineLabel()
                                    ->html()
                                    ->columnSpanFull(),
                                TextEntry::make('project_output')
                                    ->inlineLabel()
                                    ->html()
                                    ->columnSpanFull(),
                                TextEntry::make('activity_objectives')
                                    ->inlineLabel()
                                    ->html()
                                    ->columnSpanFull(),
                                TextEntry::make('activity_expected_output')
                                    ->inlineLabel()
                                    ->html()
                                    ->columnSpanFull(),
                                TextEntry::make('micro_activities')
                                    ->inlineLabel()
                                    ->html()
                                    ->columnSpanFull(),
                                TextEntry::make('modalities_of_implementation')
                                    ->inlineLabel()
                                    ->html()
                                    ->columnSpanFull(),
                            ]),
                        Tabs\Tab::make('Budget')
                            ->schema([
                                RepeatableEntry::make('budget')
                                    ->columns(4)
                                    ->columnSpanFull()
                                    ->hiddenLabel()
                                    ->schema([
                                        TextEntry::make('description')
                                            ->weight(FontWeight::Bold)
                                            ->html(),
                                        TextEntry::make('unit_cost')
                                            ->weight(FontWeight::Bold),
                                        TextEntry::make('quantity')
                                            ->weight(FontWeight::Bold),
                                        TextEntry::make('frequency')
                                            ->weight(FontWeight::Bold),
                                    ]),
                                TextEntry::make('total')
                                    ->getStateUsing(fn ($record) => Number::currency($record->total)),
                            ]),
                        Tabs\Tab::make('Personnel')
                            ->schema([
                                Section::make([
                                    TextEntry::make('preparedBy.name'),
                                    TextEntry::make('preparedBy.staffDetail.position.name')
                                        ->label('Title'),
                                    ImageEntry::make('preparedBy.signature')
                                        ->width('250px')
                                        ->label('Signature'),
                                    TextEntry::make('created_at')
                                        ->date()
                                        ->label('Date'),
                                    TextEntry::make('reviewedBy.name')
                                        ->color('success'),
                                ])
                                    ->columns(4),
                                TextEntry::make('confirmedBy.name')
                                    ->color('success')
                                    ->label('Budget availability Confirmed by'),
                                TextEntry::make('approvedBy.name')
                                    ->color('success'),
                                TextEntry::make('authorizedBy.name')
                                    ->color('success'),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('project.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('location.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('duty_station')
                    ->searchable(),
                Tables\Columns\TextColumn::make('budgetHolder.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('preparedBy.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListTermsOfReferences::route('/'),
            'create' => Pages\CreateTermsOfReference::route('/create'),
            'view' => Pages\ViewTermsOfReference::route('/{record}'),
            'edit' => Pages\EditTermsOfReference::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereAny([
                'prepared_by',
                'request_review',
                'request_approval',
                'request_authorization',
                'request_confirmation',
            ], Auth::id())
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
