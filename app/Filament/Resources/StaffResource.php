<?php

namespace App\Filament\Resources;

use App\Enums\BloodGroup;
use App\Filament\Resources\StaffResource\Pages;
use App\Filament\Staff\Resources\TaskResource;
use App\Models\Activity;
use App\Models\Department;
use App\Models\Position;
use App\Models\Role;
use App\Models\StaffDetail;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Actions\Action as ActionsAction;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Ysfkaya\FilamentPhoneInput\Infolists\PhoneEntry;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;

class StaffResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'HR';

    protected static ?string $modelLabel = 'Staff';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(StaffDetail::getForm());
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('name'),
                TextEntry::make('email')
                    ->icon('heroicon-o-envelope')
                    ->weight(FontWeight::Bold)
                    ->copyable(),
                TextEntry::make('staffDetail.department.name')
                    ->icon('heroicon-o-building-office-2')
                    ->label('Department')
                    ->weight(FontWeight::Bold),
                TextEntry::make('staffDetail.position.name')
                    ->label('Position')
                    ->weight(FontWeight::Bold),
                TextEntry::make('roles.name'),
                Fieldset::make('Other Details')
                    ->schema([
                        TextEntry::make('staffDetail.blood_group')
                            ->label('Blood Group'),
                        TextEntry::make('staffDetail.dob')
                            ->label('Date of Birth')
                            ->date(),
                        PhoneEntry::make('staffDetail.phone_number')
                            ->label('Phone')
                            ->displayFormat(PhoneInputNumberType::INTERNATIONAL)
                            ->icon('heroicon-o-phone'),
                        PhoneEntry::make('staffDetail.emergency_contact_number')
                            ->label('Emergency Contact Number')
                            ->displayFormat(PhoneInputNumberType::INTERNATIONAL)
                            ->icon('heroicon-o-phone'),
                        TextEntry::make('staffDetail.address')
                            ->html()
                            ->columnSpanFull()
                            ->label('Address'),
                    ]),
                Tabs::make('Tabs')
                    ->columnSpanFull()
                    ->tabs([
                        Tabs\Tab::make('Projects')
                            ->badge(fn ($record) => $record->projects()->count())
                            ->schema([
                                RepeatableEntry::make('projects')
                                    ->columns(3)
                                    ->hiddenLabel()
                                    ->schema([
                                        TextEntry::make('name'),
                                        TextEntry::make('code'),
                                        TextEntry::make('project_involvement')
                                            ->getStateUsing(fn ($record) => $record->pivot->project_involvement_percentage.'%')
                                            ->suffixActions([
                                                Action::make('project')
                                                    ->icon('heroicon-o-eye')
                                                    ->tooltip('View Project')
                                                    ->color('fuchsia')
                                                    ->url(fn ($record) => ProjectResource::getUrl('view', ['record' => $record->id])),
                                                Action::make('task')
                                                    ->icon('heroicon-o-briefcase')
                                                    ->tooltip('Assign Task')
                                                    ->color('info')
                                                    ->form([
                                                        Grid::make(2)
                                                            ->schema([
                                                                TextInput::make('title')
                                                                    ->required(),
                                                                DatePicker::make('deadline')
                                                                    ->required()
                                                                    ->native(false),
                                                            ]),
                                                        Select::make('activity_id')
                                                            ->searchable()
                                                            ->preload()
                                                            ->reactive()
                                                            ->options(fn ($record) => Activity::query()
                                                                ->where('project_id', $record->id)
                                                                ->get()
                                                                ->pluck('name', 'id')->toArray()
                                                            )
                                                            ->required()
                                                            ->label('Activity'),
                                                        RichEditor::make('description')
                                                            ->columnSpanFull(),
                                                    ])
                                                    ->action(function (array $data, $record) {

                                                        $task = $record->tasks()->create([
                                                            'user_id' => $userId = $record->pivot->user_id,
                                                            'deadline' => $data['deadline'],
                                                            'activity_id' => $data['activity_id'],
                                                            'description' => $data['description'],
                                                            'title' => $data['title'],
                                                        ]);

                                                        // Notify recipient
                                                        $recipient = User::find($userId);

                                                        $recipient->notify(
                                                            Notification::make()
                                                                ->title('New Task')
                                                                ->body('Task for project '.$record->code)
                                                                ->icon('heroicon-o-briefcase')
                                                                ->iconColor('info')
                                                                ->actions([
                                                                    ActionsAction::make('view')
                                                                        ->markAsRead()
                                                                        ->color('info')
                                                                        ->icon('heroicon-o-eye')
                                                                        ->url(TaskResource::getUrl('view', ['record' => $task->id], panel: 'staff')),
                                                                ])
                                                                ->toDatabase()

                                                        );

                                                    }),
                                            ]),
                                    ]),
                            ]),
                        Tabs\Tab::make('Tasks')
                            ->badge(fn ($record) => $record->tasks()->count())
                            ->schema([
                                RepeatableEntry::make('tasks')
                                    ->columns(4)
                                    ->hiddenLabel()
                                    ->schema([
                                        TextEntry::make('project.code'),
                                        TextEntry::make('title'),
                                        TextEntry::make('deadline')
                                            ->date(),
                                        TextEntry::make('status'),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->copyable(),
                TextColumn::make('projects')
                    ->getStateUsing(fn ($record) => $record->projects()->count()),
                TextColumn::make('staffDetail.department.name')
                    ->label('Department'),
                TextColumn::make('staffDetail.position.name')
                    ->label('Position'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                EditAction::make()
                    ->icon('heroicon-o-pencil-square')
                    ->color('warning')
                    ->fillForm(fn ($record): array => [
                        'department_id' => $record->staffDetail?->department->id,
                        'position_id' => $record->staffDetail?->position->id,
                        'dob' => $record->staffDetail?->dob,
                        'address' => $record->staffDetail?->address,
                        'phone_number' => $record->staffDetail?->phone_number,
                        'emergency_contact_number' => $record->staffDetail?->emergency_contact_number,
                        'blood_group' => $record->staffDetail?->blood_group,
                        'date_of_employment' => $record->staffDetail?->date_of_employment,
                    ])
                    ->form([
                        Grid::make(2)
                            ->schema([
                                Select::make('department_id')
                                    ->options(Department::all()->pluck('name', 'id'))
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->label('Department')
                                    ->createOptionForm(Department::getForm())
                                    ->createOptionModalHeading('Add Department')
                                    ->createOptionUsing(function (array $data): int {
                                        return Department::create($data)->getKey();
                                    })
                                    ->required(),
                                Select::make('position_id')
                                    ->visible(fn (Get $get) => $get('department_id'))
                                    ->options(fn (Get $get) => Position::where('department_id', $get('department_id'))->pluck('name', 'id')->toArray())
                                    ->searchable()
                                    ->preload()
                                    ->reactive()
                                    ->label('Position')
                                    ->required()
                                    ->createOptionForm(Position::getForm())
                                    ->createOptionModalHeading('Add Position')
                                    ->createOptionUsing(function (array $data): int {
                                        return Position::create($data)->getKey();
                                    }),
                            ]),
                        Grid::make(2)
                            ->schema([
                                DatePicker::make('date_of_employment')
                                    ->required()
                                    ->native(false),
                                Toggle::make('hod')
                                    ->label('Make Head of Department?')
                                    ->inline(false)
                                    ->visible(fn (Get $get) => $get('department_id')),
                            ]),
                        RichEditor::make('address')
                            ->columnSpanFull(),
                        Grid::make(2)
                            ->schema([
                                DatePicker::make('dob')
                                    ->required()
                                    ->label('Date of Birth')
                                    ->native(false),
                                Select::make('blood_group')
                                    ->default(BloodGroup::DEFAULT)
                                    ->enum(BloodGroup::class)
                                    ->options(BloodGroup::class)
                                    ->searchable(),
                            ]),
                        Grid::make(2)
                            ->schema([
                                PhoneInput::make('phone_number')
                                    ->defaultCountry('NG')
                                    ->displayNumberFormat(PhoneInputNumberType::INTERNATIONAL)
                                    ->focusNumberFormat(PhoneInputNumberType::INTERNATIONAL),
                                PhoneInput::make('emergency_contact_number')
                                    ->defaultCountry('NG')
                                    ->displayNumberFormat(PhoneInputNumberType::INTERNATIONAL)
                                    ->focusNumberFormat(PhoneInputNumberType::INTERNATIONAL),
                            ]),
                    ])
                    ->modalSubmitActionLabel('Update Details')
                    ->action(function (array $data, $record) {

                        if ($data['hod']) {

                            $department = Department::find($data['department_id']);

                            $department->hod_id = $record->id;

                            $department->save();

                        }

                        unset($data['hod']);

                        $record->staffDetail ? $record->staffDetail()->update($data) : $record->staffDetail()->create($data);

                    }),
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
            'index' => Pages\ListStaff::route('/'),
            'create' => Pages\CreateStaff::route('/create'),
            'view' => Pages\ViewStaff::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->role(Role::STAFF)
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getGlobalSearchResultUrl(Model $record): string
    {
        return StaffResource::getUrl('view', ['record' => $record]);
    }
}
