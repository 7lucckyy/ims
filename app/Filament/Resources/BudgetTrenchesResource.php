<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BudgetTrenchesResource\Pages;
use App\Models\BudgetTrench;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BudgetTrenchesResource extends Resource
{
    protected static ?string $model = BudgetTrench::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationGroup = 'Finance';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema(BudgetTrench::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('project.budget_code')
                    ->label('Budget Code')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')

                    ->label('Amount')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('transaction_date')
                    ->label('Transaction Date')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Start Date')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->label('End Date')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('amount')
                    ->money(),
            ])
            ->filters([
                SelectFilter::make('project_id')
                    ->label('Project')
                    ->relationship('project', 'budget_code')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(infolist $Infolist): Infolist
    {
        return $Infolist
            ->schema([
                Section::make('Budget Trenches Details')
                    ->columns(3)
                    ->description('Project details, transaction details, and budget trenches...')
                    ->schema([
                        TextEntry::make('project.name')
                            ->label('Project Name')
                            ->columnSpan(3),

                        TextEntry::make('amount')
                            ->label('Amount')
                            ->money('project.currency', true),

                        TextEntry::make('transaction_date')
                            ->label('Date of Transaction')
                            ->date(),

                        TextEntry::make('start_date')
                            ->label('Start Date')
                            ->date(),

                        TextEntry::make('end_date')
                            ->label('End Date')
                            ->date(),
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
            'index' => Pages\ListBudgetTrenches::route('/'),
            'create' => Pages\CreateBudgetTrenches::route('/create'),
            'edit' => Pages\EditBudgetTrenches::route('/{record}/edit'),
        ];
    }
}
