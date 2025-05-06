<?php

namespace App\Filament\Logistics\Resources;

use App\Filament\Logistics\Resources\StockOutResource\Pages;
use App\Models\Department;
use App\Models\Project;
use App\Models\Staff;
use App\Models\Stock;
use App\Models\StockOut;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StockOutResource extends Resource
{
    protected static ?string $model = StockOut::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('name_of_staff')
                    ->options(Staff::all()->pluck('staffId'))
                    ->label('Staff Id'),
                Forms\Components\Select::make('department_unit')
                    ->options(Department::all()->pluck('name'))
                    ->required()
                    ->label('Department/Unit'),
                Forms\Components\Select::make('project_name')
                    ->options(Project::all()->pluck('name')),
                Forms\Components\Select::make('name_of_items')
                    ->options(self::allStocks())
                    ->required(),
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('approved_by'),
                Forms\Components\DatePicker::make('date_of_collection')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('stock_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name_of_staff')
                    ->searchable(),
                Tables\Columns\TextColumn::make('department_unit')
                    ->searchable(),
                Tables\Columns\TextColumn::make('project_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name_of_items')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_of_collection')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->searchable(),
                Tables\Columns\TextColumn::make('approved_by')
                    ->searchable(),
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
            'index' => Pages\ListStockOuts::route('/'),
            'create' => Pages\CreateStockOut::route('/create'),
            'edit' => Pages\EditStockOut::route('/{record}/edit'),
        ];
    }

    private static function allStocks(): \Illuminate\Support\Collection|array
    {
        $stocks = Stock::all();
        $names = [];
        foreach ($stocks as $stock) {
            if ($stock->ITEM_DESCRIPTION === null) {
                continue;
            }
            $names[$stock->ITEM_DESCRIPTION] = $stock->ITEM_DESCRIPTION;
        }

        return $names;
    }
}
