<?php

namespace App\Filament\Finance\Resources;

use App\Filament\Finance\Resources\ExpenseResource\Pages;
use App\Models\Expense;
use Dompdf\FrameDecorator\Text;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use PhpOffice\PhpSpreadsheet\Calculation\TextData\Search;

class ExpenseResource extends Resource
{
    protected static ?string $model = Expense::class;


    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Expense::getForm());
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('budgetTrench.project.name')
                    ->label('Project Name')
                    ->searchable(),
                TextColumn::make('vendor.name')
                    ->label('Vendor Name')
                    ->searchable(),
                TextColumn::make('total_amount')
                    ->money('NGN', true),
                TextColumn::make('category.name')
                    ->label('Category')
                    ->searchable(),
                TextColumn::make('transaction_date')
                    ->date(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('budget_trench_id')
                    ->relationship('budgetTrench', 'code')
                    ->searchable()
                    ->preload(),
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
            'index' => Pages\ListExpenses::route('/'),
            'create' => Pages\CreateExpense::route('/create'),
            'edit' => Pages\EditExpense::route('/{record}/edit'),
        ];
    }
}
