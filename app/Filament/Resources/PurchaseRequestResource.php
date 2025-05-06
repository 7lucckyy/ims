<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PurchaseRequestResource\Pages;
use App\Models\PurchaseRequest;
use Filament\Forms\Form;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Number;

class PurchaseRequestResource extends Resource
{
    protected static ?string $model = PurchaseRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationGroup = 'Procurement';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema(PurchaseRequest::getForm());
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Grid::make(3)
                    ->schema([
                        TextEntry::make('procurement_threshold')
                            ->getStateUsing(fn ($record) => Number::currency($record->procurement_threshold, $record->currency->abbr))
                            ->weight(FontWeight::Bold)
                            ->color('success')
                            ->inlineLabel(),
                        TextEntry::make('sole_quotation')
                            ->getStateUsing(fn ($record) => Number::currency($record->sole_quotation, $record->currency->abbr))
                            ->weight(FontWeight::Bold)
                            ->color('success')
                            ->inlineLabel(),
                        TextEntry::make('negotiated_procedures')
                            ->getStateUsing(fn ($record) => Number::currency($record->negotiated_procedures, $record->currency->abbr))
                            ->weight(FontWeight::Bold)
                            ->color('success')
                            ->inlineLabel(),
                    ]),
                Split::make([
                    Section::make([
                        TextEntry::make('state.name')
                            ->weight(FontWeight::Bold),
                        TextEntry::make('office')
                            ->weight(FontWeight::Bold),
                        TextEntry::make('priority')
                            ->weight(FontWeight::Bold)
                            ->badge(),
                        Fieldset::make('Destination of Goods')
                            ->columns(1)
                            ->schema([
                                TextEntry::make('requestedBy.name')
                                    ->weight(FontWeight::Bold)
                                    ->label('Name:')
                                    ->inlineLabel(),
                                TextEntry::make('requestedBy.email')
                                    ->weight(FontWeight::Bold)
                                    ->label('Email:')
                                    ->inlineLabel(),
                                TextEntry::make('requestedBy.staffDetail.phone_number')
                                    ->weight(FontWeight::Bold)
                                    ->label('Phone No:')
                                    ->inlineLabel(),
                                TextEntry::make('address')
                                    ->weight(FontWeight::Bold)
                                    ->html(),
                            ]),
                    ]),
                    Section::make([
                        TextEntry::make('request_date')
                            ->weight(FontWeight::Bold)
                            ->label('Date request prepared'),
                        TextEntry::make('department.name')
                            ->weight(FontWeight::Bold),
                        TextEntry::make('required_date')
                            ->weight(FontWeight::Bold)
                            ->label('Date goods are required'),
                        TextEntry::make('status')
                            ->weight(FontWeight::Bold)
                            ->badge(),
                        TextEntry::make('end_date')
                            ->label('Project end date')
                            ->weight(FontWeight::Bold),
                        TextEntry::make('delivery')
                            ->label('Preferred method of delivery')
                            ->weight(FontWeight::Bold),
                        TextEntry::make('purpose')
                            ->weight(FontWeight::Bold)
                            ->label('Purpose of Request')
                            ->html()
                            ->columnSpanFull(),
                        TextEntry::make('donor_requirements')
                            ->weight(FontWeight::Bold)
                            ->label('Any donor requirements exceeding GPON Procurement Policy')
                            ->html()
                            ->columnSpanFull(),
                        TextEntry::make('import_restrictions')
                            ->weight(FontWeight::Bold)
                            ->label('Any import restrictions or limitations on transport of goods(if known)')
                            ->html()
                            ->columnSpanFull(),
                    ])
                        ->columns(2),
                ])
                    ->columnSpanFull(),
                Fieldset::make('Items')
                    ->schema([
                        RepeatableEntry::make('items')
                            ->columns(6)
                            ->columnSpanFull()
                            ->hiddenLabel()
                            ->schema([
                                TextEntry::make('name'),
                                TextEntry::make('description')
                                    ->html(),
                                TextEntry::make('form')
                                    ->label('Unit/Form'),
                                TextEntry::make('frequency'),
                                TextEntry::make('quantity'),
                                TextEntry::make('unit_cost'),
                                TextEntry::make('amount'),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('department.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('request_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_cost')
                    ->money(fn ($record) => $record->currency->abbr)
                    ->sortable(),
                Tables\Columns\TextColumn::make('approvedBy.name')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('requestedBy.name')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListPurchaseRequests::route('/'),
            'create' => Pages\CreatePurchaseRequest::route('/create'),
            'view' => Pages\ViewPurchaseRequest::route('/{record}'),
            'edit' => Pages\EditPurchaseRequest::route('/{record}/edit'),
        ];
    }
}
