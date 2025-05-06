<?php

namespace App\Filament\Hr\Resources;

use App\Filament\Hr\Resources\AttendanceResource\Pages;
use App\Models\Attendance;
use App\Models\Staff;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-bars-3-bottom-left';


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
                        $staff = Staff::where([
                            'staffId' => $model->staff_id,
                        ])->get()->first();
                        if ($staff == null) {
                            return '';
                        }

                        return $staff->firstName . ' ' . $staff->lastName;
                    }),
                TextColumn::make('clock_in_date')
                    ->label('Date'),
                TextColumn::make('clock_in_time')
                    ->label('Time In'),
                TextColumn::make('Time Spent')
                    ->default(function (Model $model) {
                        $date = $model->clock_in_date . ' ' . $model->clock_in_time;

                        $date = \Carbon\Carbon::parse($date);
                        $out = \Carbon\Carbon::parse($model->clock_out);

                        return $date->diff($out)->hours . ' hours';
                    }),
                TextColumn::make('clock_out')
                    ->label('Time Out'),
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
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }
}
