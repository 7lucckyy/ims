<?php

namespace App\Filament\Resources;

use App\Enums\DocumentType;
use App\Filament\Resources\DepartmentResource\Pages;
use App\Models\Department;
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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class DepartmentResource extends Resource
{
    protected static ?string $model = Department::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';

    protected static ?string $navigationGroup = 'HR';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Department::getForm());
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        $department = $infolist->record;

        return $infolist
            ->schema([
                Section::make('Department Information')
                    ->description('Name, Staff & Head of Dept.')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('name'),
                        TextEntry::make('staff')
                            ->getStateUsing(fn ($record) => $record->staff()->count())
                            ->icon('heroicon-o-user-group'),
                        TextEntry::make('hod.name')
                            ->label('Head of Department')
                            ->icon('heroicon-o-user')
                            ->url(fn ($record) => StaffResource::getUrl('view', ['record' => $record->hod_id])),
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
                                                Action::make('view')
                                                    ->icon('heroicon-o-eye')
                                                    ->tooltip('View Staff')
                                                    ->url(fn ($record) => StaffResource::getUrl('view', ['record' => $record->user->id])),
                                                Action::make('hod')
                                                    ->visible(fn ($record) => $record->user->id != $department->hod->id)
                                                    ->color('blue')
                                                    ->icon('heroicon-o-user')
                                                    ->requiresConfirmation()
                                                    ->modalDescription(fn ($record) => 'Are you sure you want to make '.$record->user->name.' the Head of this Department?')
                                                    ->modalIcon('heroicon-o-user')
                                                    ->label('Head of Department')
                                                    ->action(function ($record) use ($department) {
                                                        $department->update([
                                                            'hod_id' => $record->user->id,
                                                        ]);
                                                    }),
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
                                            ->date(),
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
                TextColumn::make('hod.name'),
                TextColumn::make('staff')
                    ->getStateUsing(fn ($record) => $record->staff()->count()),
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
            //            'create' => Pages\CreateDepartment::route('/create'),
            //            'edit' => Pages\EditDepartment::route('/{record}/edit'),
            'view' => Pages\ViewDepartment::route('/{record}'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'hod.name'];
    }

    public static function getGlobalSearchResultUrl(Model $record): string
    {
        return DepartmentResource::getUrl('view', ['record' => $record]);
    }
}
