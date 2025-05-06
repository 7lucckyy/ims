<?php

namespace App\Filament\Staff\Resources;

use App\Enums\DocumentType;
use App\Enums\LeaveStatus;
use App\Filament\Staff\Resources\DepartmentResource\Pages;
use App\Models\Department;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class DepartmentResource extends Resource
{
    protected static ?string $model = Department::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('hod_id')
                    ->relationship('hod', 'name'),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        $department = $infolist->record;

        return $infolist
            ->schema([
                Section::make('Department Information')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('name'),
                        TextEntry::make('staff')
                            ->getStateUsing(fn ($record) => $record->staff()->count())
                            ->icon('heroicon-o-user-group'),
                    ]),
                Tabs::make('Tabs')
                    ->columnSpanFull()
                    ->tabs([
                        Tabs\Tab::make('Staff')
                            ->badge(fn ($record) => $record->staff()->count())
                            ->schema([
                                RepeatableEntry::make('staff')
                                    ->columns(2)
                                    ->hiddenLabel()
                                    ->schema([
                                        TextEntry::make('user.name')
                                            ->label('Name'),
                                        TextEntry::make('position.name')
                                            ->label('Position')
                                            ->suffixActions([
                                                Action::make('leave')
                                                    ->color('fuchsia')
                                                    ->icon('heroicon-o-calendar-date-range')
                                                    ->tooltip('Apply Leave')
                                                    ->form([
                                                        Grid::make(2)
                                                            ->schema([
                                                                TextInput::make('period_of_leave')
                                                                    ->required(),
                                                                RichEditor::make('reason')
                                                                    ->required()
                                                                    ->columnSpanFull(),
                                                            ]),
                                                        Grid::make(2)
                                                            ->schema([
                                                                DatePicker::make('start_date')
                                                                    ->required(),
                                                                DatePicker::make('aspected_resumption_date')
                                                                    ->required(),
                                                            ]),
                                                        FileUpload::make('document')
                                                            ->columnSpanFull(),
                                                    ])
                                                    ->action(function (array $data, $record) use ($department) {

                                                        $leave = $record->user->leaves()->create([
                                                            'department_id' => $department->id,
                                                            'reason' => $data['reason'],
                                                            'period_of_leave' => $data['period_of_leave'],
                                                            'start_date' => $data['start_date'],
                                                            'aspected_resumption_date' => $data['aspected_resumption_date'],
                                                        ]);

                                                        if ($data['document']) {
                                                            $leave->document()->create([
                                                                'type' => DocumentType::Attachment,
                                                                'file' => $data['document'],
                                                            ]);
                                                        }

                                                    }),
                                            ]),
                                    ]),
                            ]),
                        Tabs\Tab::make('Leaves')
                            ->badge(fn ($record) => $record->leaves()->count())
                            ->schema([
                                RepeatableEntry::make('leaves')
                                    ->columns(4)
                                    ->hiddenLabel()
                                    ->schema([
                                        TextEntry::make('user.name')
                                            ->label('Staff'),
                                        TextEntry::make('approval')
                                            ->label('Status')
                                            ->badge(),
                                        TextEntry::make('start_date')
                                            ->date(),
                                        TextEntry::make('aspected_resumption_date')
                                            ->label('Resumption Date')
                                            ->date()
                                            ->suffixActions([
                                                Action::make('approve')
                                                    ->icon('heroicon-o-check-badge')
                                                    ->color('success')
                                                    ->visible(fn ($record) => $record->approval->value === LeaveStatus::Pending->value)
                                                    ->requiresConfirmation()
                                                    ->action(function ($record) {
                                                        $record->approve();
                                                    }),
                                                Action::make('reject')
                                                    ->icon('heroicon-o-x-circle')
                                                    ->color('danger')
                                                    ->visible(fn ($record) => $record->approval->value === LeaveStatus::Pending->value)
                                                    ->requiresConfirmation()
                                                    ->action(function ($record) {
                                                        $record->reject();
                                                    }),
                                            ]),
                                    ]),
                            ]),
                        Tabs\Tab::make('Inventories')
                            ->badge(fn ($record) => $record->inventories()->count())
                            ->schema([
                                RepeatableEntry::make('inventories')
                                    ->columns(5)
                                    ->hiddenLabel()
                                    ->schema([
                                        TextEntry::make('name'),
                                        TextEntry::make('batch_no'),
                                        TextEntry::make('donor.name'),
                                        TextEntry::make('approvedBy.name'),
                                        TextEntry::make('total_quantity'),
                                    ]),
                            ]),
                        Tabs\Tab::make('Purchase Requests')
                            ->badge(fn ($record) => $record->purchaseRequests()->count())
                            ->schema([
                                RepeatableEntry::make('purchaseRequests')
                                    ->columns(3)
                                    ->hiddenLabel()
                                    ->schema([
                                        TextEntry::make('vendor.name'),
                                        TextEntry::make('status')
                                            ->badge(),
                                        TextEntry::make('total_cost')
                                            ->money(fn ($record) => $record->currency->abbr),

                                    ]),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
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
            'index' => Pages\ListDepartments::route('/'),
            'view' => Pages\ViewDepartment::route('/{record}'),
            'edit' => Pages\EditDepartment::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()?->isHod();
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('hod_id', Auth::id())
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
