<?php

namespace App\Filament\Hr\Resources;

use App\Filament\Hr\Resources\AppraisalResource\Pages;
use App\Models\Appraisal;
use Filament\Forms\Form;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AppraisalResource extends Resource
{
    protected static ?string $model = Appraisal::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Appraisal::getForm());
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('staff.name'),
                TextEntry::make('cycle')
                    ->label('Appraisal Cycle'),
                TextEntry::make('method')
                    ->label('Appraisal Method'),
                TextEntry::make('project.name'),
                TextEntry::make('feedback')
                    ->columnSpanFull()
                    ->html(),
                TextEntry::make('discussion')
                    ->columnSpanFull()
                    ->html(),
                Fieldset::make('Evaluation Criteria')
                    ->schema([
                        RepeatableEntry::make('evaluation_criteria')
                            ->columns(2)
                            ->hiddenLabel()
                            ->schema([
                                TextEntry::make('criteria'),
                                TextEntry::make('remarks')
                                    ->html(),
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
                Tables\Columns\TextColumn::make('staff.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cycle'),
                Tables\Columns\TextColumn::make('method'),
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
            'index' => Pages\ListAppraisals::route('/'),
            'create' => Pages\CreateAppraisal::route('/create'),
            'view' => Pages\ViewAppraisal::route('/{record}'),
            'edit' => Pages\EditAppraisal::route('/{record}/edit'),
        ];
    }
}
