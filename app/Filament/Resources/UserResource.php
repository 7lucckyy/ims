<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Hidden;
use Illuminate\Support\Str;


class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'User Management';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Fieldset::make('User Credentials')
                            ->columns(1)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required(),
                                Forms\Components\TextInput::make('email')
                                    ->unique(User::class, ignoreRecord: true)
                                    ->email()
                                    ->required(),
                                    Hidden::make('staffId')
                            ->default(Str::uuid()->toString()),
                                Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('password')
                                            ->revealable(true)
                                            ->confirmed()
                                            ->password()
                                            ->visibleOn('create')
                                            ->required(),
                                        TextInput::make('password_confirmation')
                                            ->password()
                                            ->revealable()
                                            ->required()
                                            ->visibleOn('create'),
                                    ]),
                            ]),
                    ])
                    ->columnSpan(8),
                Group::make()
                    ->schema([
                        Fieldset::make('User Roles')
                            ->columns(1)
                            ->schema([
                                CheckboxList::make('roles')
                                    ->relationship('roles', 'name')
                                    ->required(),
                            ]),
                    ])
                    ->columnSpan(4),
            ])
            ->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->copyable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
