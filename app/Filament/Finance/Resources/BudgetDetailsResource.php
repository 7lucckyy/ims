<?php

namespace App\Filament\Finance\Resources;

use App\Filament\Finance\Resources\BudgetDetailsResource\Pages;
use App\Models\BudgetDetail;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BudgetDetailsResource extends Resource
{
    protected static ?string $model = BudgetDetail::class;

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-bars-3';

    // Form Schema
    public static function form(Form $form): Form
    {
        return $form->schema(BudgetDetail::getForm());

    }

    // Define the table schema (columns, filters, actions)
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('line')
                    ->label('Line')
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->sortable(),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount')
                    ->sortable()
                    ->money('NGN', true),
                Tables\Columns\TextColumn::make('project.code') // Accessing 'description'
                    ->label('Project Code')
                    ->sortable()
                    ->searchable(),

            ])
            ->filters([
                SelectFilter::make('project_id')
                    ->label('Project')
                    ->relationship('project', 'code')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('budget_trench_id')
                    ->label('Budget Trench')
                    ->relationship('budgetTrench', 'code')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBudgetDetails::route('/'),
            'create' => Pages\CreateBudgetDetails::route('/create'),
            'edit' => Pages\EditBudgetDetails::route('/{record}/edit'),
        ];
    }
}
