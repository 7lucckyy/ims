<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuthorizationResource\Pages;
use App\Models\Authorization;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AuthorizationResource extends Resource
{
    protected static ?string $model = Authorization::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
            'index' => Pages\ListAuthorizations::route('/'),
            'create' => Pages\CreateAuthorization::route('/create'),
            'edit' => Pages\EditAuthorization::route('/{record}/edit'),
        ];
    }
}
