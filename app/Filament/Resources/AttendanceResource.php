<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Attendance;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\AttendanceResource\Pages;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-bars-3-bottom-left';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('staff_id')
                ->label('Staff ID')
                ->required(),

            Forms\Components\DatePicker::make('clock_in_date')
                ->label('Date')
                ->required(),

            Forms\Components\TextInput::make('clock_in')
                ->label('Time In')
                ->required(),

            Forms\Components\TextInput::make('clock_out')
                ->label('Time Out')
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user_name')
                    ->label('Name')
                    ->default(function (Model $model) {
                        $user = User::where('staffid', $model->staff_id)->first();
                        return $user ? $user->name : 'Unknown';
                    }),

                TextColumn::make('clock_in_date')
                    ->label('Date'),

                TextColumn::make('clock_in_time')
                    ->label('Time In'),

                TextColumn::make('clock_out')
                    ->label('Time Out'),

                TextColumn::make('duration')
                ->label('Time Spent')
                ->default(fn ($record) => $record->duration ??''),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('staff_id')
                    ->label('Filter by User')
                    ->options(
                        User::pluck('name', 'staffid')->toArray()
                    ),
            ])
           
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }
}