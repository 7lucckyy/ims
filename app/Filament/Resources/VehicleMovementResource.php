<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehicleMovementResource\Pages;
use App\Models\Department;
use App\Models\Project;
use App\Models\VehicleMovement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class VehicleMovementResource extends Resource
{
    protected static ?string $model = VehicleMovement::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Logistics';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('user_id')
                    ->default(auth()->user()->id)
                    ->required(),
                Forms\Components\Select::make('department')
                    ->options(Department::all()->pluck('name', 'name'))
                    ->required(),
                Forms\Components\Select::make('project')
                    ->options(Project::all()->pluck('name', 'name'))
                    ->required(),
                Forms\Components\Textarea::make('mission')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\DatePicker::make('date')
                    ->required(),
                Forms\Components\TextInput::make('day')
                    ->required(),
                Forms\Components\TextInput::make('destination_from')
                    ->required(),
                Forms\Components\TextInput::make('location_to')
                    ->required(),
                Forms\Components\TimePicker::make('expected_arrival')
                    ->label('Expected arrival time')
                    ->required(),
                Forms\Components\TimePicker::make('expected_departure_time')
                    ->required(),
                Forms\Components\TextInput::make('location')
                    ->placeholder('Mdg/Y/Abj/Dtr')
                    ->required(),
                Forms\Components\TextInput::make('passenger')
                    ->required(),
                Forms\Components\TextInput::make('luggage')
                    ->required(),
                Forms\Components\TextInput::make('remark'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('approval')
                    ->badge(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('department')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('day')
                    ->searchable(),
                Tables\Columns\TextColumn::make('destination_from')
                    ->searchable(),
                Tables\Columns\TextColumn::make('location_to')
                    ->searchable(),
                Tables\Columns\TextColumn::make('expected_arrival')
                    ->searchable(),
                Tables\Columns\TextColumn::make('location')
                    ->searchable(),
                Tables\Columns\TextColumn::make('project')
                    ->searchable(),
            ])
            ->query(function () {
                return VehicleMovement::latest();
            })
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
            'index' => Pages\ListVehicleMovements::route('/'),
            'create' => Pages\CreateVehicleMovement::route('/create'),
            'view' => Pages\ViewVehicleMovement::route('/{record}/view'),
            'edit' => Pages\EditVehicleMovement::route('/{record}/edit'),
        ];
    }
}
