<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Attendance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Blade;
use App\Filament\Resources\AttendanceResource\Pages;
use Illuminate\Database\Eloquent\Model;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;
    protected static ?string $navigationIcon = 'heroicon-o-bars-3-bottom-left';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('staff_id')
                ->label('Staff')
                ->options(User::pluck('name', 'staffid')->toArray())
                ->required()
                ->searchable(),

            Forms\Components\DatePicker::make('clock_in_date')
                ->label('Date')
                ->required(),

            Forms\Components\TextInput::make('clock_in_time')
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
                // Directly display staff_id (which is actually staffid)
                TextColumn::make('staff_id')
                    ->label('Staff ID')
                    ->sortable()
                    ->searchable(),
                    
                // Display user name using a custom query
                TextColumn::make('user_name')
                    ->label('Name')
                    ->getStateUsing(function (Attendance $record) {
                        $user = User::where('staffid', $record->staff_id)->first();
                        return $user ? $user->name : 'Unknown';
                    }),

                TextColumn::make('clock_in_date')
                    ->label('Date')
                    ->date()
                    ->sortable(),

                TextColumn::make('clock_in_time')
                    ->label('Time In')
                    ->time(),

                TextColumn::make('clock_out')
                    ->label('Time Out')
                    ->time(),

                TextColumn::make('time_spent')
                    ->label('Time Spent')
                    ->getStateUsing(function (Attendance $record) {
                        if (!$record->clock_in_time || !$record->clock_out) return '-';
                        
                        $clockIn = Carbon::parse($record->clock_in_date . ' ' . $record->clock_in_time);
                        $clockOut = Carbon::parse($record->clock_in_date . ' ' . $record->clock_out);
                        
                        // Handle overnight shifts
                        if ($clockOut < $clockIn) {
                            $clockOut->addDay();
                        }
                        
                        $diff = $clockIn->diff($clockOut);
                        return sprintf('%d hrs %d mins', $diff->h, $diff->i);
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('staff_id')
                    ->label('Filter by User')
                    ->options(User::pluck('name', 'staffid')->toArray())
                    ->searchable(),
            ])
            ->headerActions([
                Action::make('export_timesheet')
                    ->label('Export Timesheet')
                    ->icon('heroicon-o-document-arrow-down')
                    ->form([
                        Forms\Components\Select::make('staff_id')
                            ->label('Select Staff')
                            ->options(User::pluck('name', 'staffid')->toArray())
                            ->required()
                            ->searchable()
                            ->native(false),
                        
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Start Date')
                            ->required()
                            ->default(now()->startOfMonth())
                            ->displayFormat('M d, Y'),
                        
                        Forms\Components\DatePicker::make('end_date')
                            ->label('End Date')
                            ->required()
                            ->default(now()->endOfMonth())
                            ->displayFormat('M d, Y'),
                    ])
                    ->action(function (array $data) {
                        $user = User::with(['staffDetail.department', 'staffDetail.position', 'projects'])
                            ->where('staffid', $data['staff_id'])
                            ->first();
                        
                        if (!$user) {
                            throw new \Exception('User not found');
                        }
                        
                        $startDate = Carbon::parse($data['start_date']);
                        $endDate = Carbon::parse($data['end_date']);
                        
                        if ($startDate->diffInDays($endDate) > 31) {
                            throw new \Exception('Date range cannot exceed 31 days');
                        }
                        
                        $attendances = Attendance::where('staff_id', $data['staff_id'])
                            ->whereBetween('clock_in_date', [$startDate, $endDate])
                            ->get()
                            ->keyBy(function ($item) {
                                return Carbon::parse($item->clock_in_date)->format('Y-m-d');
                            });
                        
                        $period = $startDate->daysUntil($endDate->addDay());
                        
                        $filename = "Timesheet_{$user->name}_" . 
                                   $startDate->format('M_d') . '_to_' . 
                                   $endDate->format('d') . '.pdf';
                        
                        $pdf = Pdf::loadHtml(
                            Blade::render('pdf.timesheet', [
                                'user' => $user,
                                'period' => $period,
                                'attendances' => $attendances,
                                'start_date' => $startDate,
                                'end_date' => $endDate,
                                'projects' => $user->projects
                            ])
                        )->setPaper('a4', 'landscape');
                        
                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            $filename
                        );
                    })
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