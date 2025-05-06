<?php

namespace App\Filament\Meal\Resources;

use App\Filament\Meal\Resources\ToolKitResource\Pages;
use App\Models\ToolKit;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ToolKitResource extends Resource
{
    protected static ?string $model = ToolKit::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';


    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListToolKits::route('/'),
            'create' => Pages\CreateToolKit::route('/create'),
            'edit' => Pages\EditToolKit::route('/{record}/edit'),
        ];
    }
}
