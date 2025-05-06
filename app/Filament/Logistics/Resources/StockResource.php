<?php

namespace App\Filament\Logistics\Resources;

use App\Filament\Logistics\Resources\StockResource\Pages;
use App\Models\Stock;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StockResource extends Resource
{
    protected static ?string $model = Stock::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('DATE_RECEIVED')
                    ->label('DATE'),
                Forms\Components\TextInput::make('ITEM_DESCRIPTION')
                    ->label('ITEM DESCRIPTION'),
                Forms\Components\TextInput::make('UNIT'),
                Forms\Components\TextInput::make('QTY_IN')
                    ->label('QTY IN')
                    ->live()
                    ->numeric(),
                Forms\Components\Hidden::make('QTY_OUT')
                    ->default(0),
                Forms\Components\TextInput::make('REMARKS'),
                Forms\Components\TextInput::make('RECEIVED_BY'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('DATE_RECEIVED')
                    ->label('DATE'),
                Tables\Columns\TextColumn::make('ITEM_DESCRIPTION')
                    ->label('ITEM DESCRIPTION'),
                Tables\Columns\TextColumn::make('UNIT'),
                Tables\Columns\TextColumn::make('QTY_IN')
                    ->label('QTY IN'),
                Tables\Columns\TextColumn::make('QTY_OUT')
                    ->label('QTY OUT'),
                Tables\Columns\TextColumn::make('STOCK_BALANCE')
                    ->label('STOCK BALANCE'),
                Tables\Columns\TextColumn::make('REMARKS')
                    ->label('REMARKS'),
            ])
            ->filters([
                //
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
            'index' => Pages\ListStocks::route('/'),
            'create' => Pages\CreateStock::route('/create'),
            'edit' => Pages\EditStock::route('/{record}/edit'),
        ];
    }
}
