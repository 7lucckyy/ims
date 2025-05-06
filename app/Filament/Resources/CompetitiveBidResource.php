<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompetitiveBidResource\Pages;
use App\Models\CompetitiveBid;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CompetitiveBidResource extends Resource
{
    protected static ?string $model = CompetitiveBid::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Procurement';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema(CompetitiveBid::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('currency.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('project.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('vendor.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bid_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('our_bid_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('variance_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('variance_percentage')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bid_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status'),
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
            'index' => Pages\ListCompetitiveBids::route('/'),
            'create' => Pages\CreateCompetitiveBid::route('/create'),
            'view' => Pages\ViewCompetitiveBid::route('/{record}'),
            'edit' => Pages\EditCompetitiveBid::route('/{record}/edit'),
        ];
    }
}
