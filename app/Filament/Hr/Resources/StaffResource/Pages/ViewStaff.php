<?php

namespace App\Filament\Hr\Resources\StaffResource\Pages;

use App\Enums\BloodGroup;
use App\Enums\DocumentType;
use App\Filament\Resources\StaffResource;
use App\Models\Department;
use App\Models\Position;
use App\Models\Project;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\Alignment;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;

class ViewStaff extends ViewRecord
{
    protected static string $resource = StaffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                Action::make('edit')
                    ->label('Edit Details')
                    ->icon('heroicon-o-view-columns')
                    ->color('warning')
                    ->fillForm(fn($record): array => [
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
                                    ->visible(fn(Get $get) => $get('department_id'))
                                    ->options(fn(Get $get) => Position::where('department_id', $get('department_id'))->pluck('name', 'id')->toArray())
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
                                    ->inline(false)
                                    ->label('Make Head of Department?')
                                    ->visible(fn(Get $get) => $get('department_id')),
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
                                    ->default(BloodGroup::DEFAULT )
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
                Action::make('projects')
                    ->label('Assign to Projects')
                    ->icon('heroicon-o-briefcase')
                    ->modalIcon('heroicon-o-briefcase')
                    ->modalAlignment(Alignment::Center)
                    ->modalSubmitActionLabel('Assign to Projects')
                    ->color('success')
                    ->form([
                        Repeater::make('projects')
                            ->addActionLabel('Add Project')
                            ->columns(2)
                            ->columnSpanFull()
                            ->hiddenLabel()
                            ->schema([
                                Select::make('project_id')
                                    ->label('Project')
                                    ->options(function ($record) {
                                        $inProject = $record->projects()->pluck('project_id');

                                        return Project::query()
                                            ->whereNotIn('id', $inProject)
                                            ->pluck('name', 'id');
                                    })
                                    ->createOptionForm(Project::getForm())
                                    ->createOptionModalHeading('Add Project')
                                    ->createOptionUsing(function (array $data): int {

                                        $project = Project::create([
                                            'code' => $data['code'],
                                            'name' => $data['name'],
                                            'duration' => $data['duration'],
                                            'budget' => $data['budget'],
                                            'currency_id' => $data['currency_id'],
                                        ]);

                                        collect($data['locations'])->each(function ($location) use ($project) {

                                            $project->locations()->create([
                                                'name' => $location['name'],
                                                'location' => $location['location'],
                                            ]);

                                        });

                                        empty($data['budget_file']) ?: $project->documents()->create([
                                            'file' => $data['budget_file'],
                                            'type' => DocumentType::Budget,
                                        ]);

                                        collect($data['attachments'])->each(function ($attachment) use ($project) {

                                            $project->documents()->create([
                                                'file' => $attachment,
                                                'type' => DocumentType::Attachment,
                                            ]);

                                        });

                                        return $project->getKey();
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems(),
                                TextInput::make('project_involvement_percentage')
                                    ->label('Percentage Involvement')
                                    ->numeric()
                                    ->suffix('%')
                                    ->minValue(1)
                                    ->maxValue(100),
                            ]),
                    ])
                    ->modalSubmitActionLabel('Assign Projects')
                    ->action(function (array $data, $record) {

                        $record->projects()->attach($data['projects']);

                    }),
            ]),
        ];
    }
}
