<?php

namespace App\Filament\Resources;

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

    protected static ?string $navigationGroup = 'HR';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('staff_id')
                    ->required(),
                Forms\Components\DatePicker::make('clock_in_date')
                    ->required(),
                Forms\Components\TextInput::make('clock_in_time')
                    ->required(),
                Forms\Components\TextInput::make('clock_out'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->default(function (Model $model) {
                        $staff = User::where([
                            'staffId' => $model->staff_id,
                        ])->get()->first();
                        if ($staff == null) {
                            return '';
                        }

                        return $staff->name;
                    }),
                TextColumn::make('clock_in_date')
                    ->label('Date'),
                TextColumn::make('clock_in_time')
                    ->label('Time In'),
                TextColumn::make('Time Spent')
                    ->default(function (Model $model) {
                        $date = $model->clock_in_date.' '.$model->clock_in_time;

                        $date = \Carbon\Carbon::parse($date);
                        $out = \Carbon\Carbon::parse($model->clock_out);

                        return $date->diff($out)->hours.' hours';
                    }),
                TextColumn::make('clock_out')
                    ->label('Time Out'),
            ])
            ->filters([
                date('Y-m-d') => 'Today',
                date('Y-m-d', strtotime('-1 day')) => 'Yesterday',
                date('Y-m-d', strtotime('-1 week')) => 'Last Week',
                date('Y-m-d', strtotime('-1 month')) => 'Last Month',
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
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }
}
