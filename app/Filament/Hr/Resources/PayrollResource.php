<?php

namespace App\Filament\Hr\Resources;

use App\Filament\Hr\Resources\PayrollResource\Pages;
use App\Models\Payroll;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PayrollResource extends Resource
{
    protected static ?string $model = Payroll::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';


    public static function form(Form $form): Form
    {
        return $form
            ->schema(Payroll::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Staff')
                    ->sortable(),
                Tables\Columns\TextColumn::make('monthly_gross')
                    ->money(fn ($record) => $record->currency->abbr)
                    ->sortable(),
                Tables\Columns\TextColumn::make('paye_tax')
                    ->sortable(),
                Tables\Columns\TextColumn::make('net_pay')
                    ->money(fn ($record) => $record->currency->abbr)
                    ->sortable(),
                Tables\Columns\TextColumn::make('health_insurance')
                    ->money(fn ($record) => $record->currency->abbr)
                    ->sortable(),
                Tables\Columns\TextColumn::make('pension')
                    ->money(fn ($record) => $record->currency->abbr)
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
            'index' => Pages\ListPayrolls::route('/'),
            'create' => Pages\CreatePayroll::route('/create'),
            'edit' => Pages\EditPayroll::route('/{record}/edit'),
        ];
    }
}
