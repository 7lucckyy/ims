<?php

namespace App\Filament\Hr\Resources;

use Carbon\Carbon;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Staff;
use Filament\Forms\Form;
use App\Models\Attendance;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Hr\Resources\AttendanceResource\Pages;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-bars-3-bottom-left';


   public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('staff_id')
                ->label('Staff')
                ->relationship('user', 'name') // using relationship + name field
                ->searchable()
                ->required(),

            Forms\Components\DatePicker::make('clock_in_date')
                ->label('Date')
                ->required(),

            Forms\Components\TextInput::make('clock_in_time')
                ->label('Clock-in Time')
                ->required(),

            Forms\Components\TextInput::make('clock_out')
                ->label('Clock-out Time')
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('user.name')
                ->label('Staff Name')
                ->sortable()
                ->searchable(),

            TextColumn::make('clock_in_date')
                ->label('Date'),

            TextColumn::make('clock_in_time')
                ->label('Time In'),

            TextColumn::make('clock_out')
                ->label('Time Out'),

            TextColumn::make('duration')
                ->label('Time Spent')
                ->default(function (Attendance $record) {
                    if (!$record->clock_out) return 'Still clocked in';

                    $in = Carbon::parse("{$record->clock_in_date} {$record->clock_in_time}");
                    $out = Carbon::parse($record->clock_out);

                    return $in->diff($out)->format('%h hrs %i mins');
                })
                ->color(fn (Attendance $record) => $record->clock_out ? 'success' : 'danger'),
        ])
        ->filters([])
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
