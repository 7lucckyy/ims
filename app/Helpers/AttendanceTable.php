<?php

namespace App\Helpers;

use App\Models\Attendance;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class AttendanceTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('clock_in_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('clock_in_time')
                    ->label('Time in'),
                // Tables\Columns\TextColumn::make('Range')
                //     ->default(function (Model $model) {
                //         $date = $model->clock_in_date.' '.$model->clock_in_time;
                //         $date = \Carbon\Carbon::parse($date);
                //         $out = \Carbon\Carbon::parse($model->clock_out);

                //         return $date->diff($out)->hours.' hours';
                //     }),
                Tables\Columns\TextColumn::make('clock_out')
                    ->label('Time Out'),
            ])
            ->query(function () {
                return Attendance::query()->where('staff_id', auth()->user()->staffId);
            });
    }
}
