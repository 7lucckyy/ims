<?php

namespace App\Models;

use App\Enums\BloodGroup;
use App\Enums\DocumentType;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffDetail extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'blood_group' => BloodGroup::class,
        ];
    }

    public static function getForm(): array
    {
        return [
            Group::make()
                ->schema([
                    Fieldset::make('Staff Credentials')
                        ->schema([
                            TextInput::make('name')
                                ->required(),
                            TextInput::make('email')
                                ->required()
                                ->email()
                                ->unique('users', 'email', ignoreRecord: true),
                        ]),
                    Fieldset::make('Projects')
                        ->schema([
                            Repeater::make('projects')
                                ->addActionLabel('Add Project')
                                ->columns(2)
                                ->columnSpanFull()
                                ->hiddenLabel()
                                ->schema([
                                    Select::make('project_id')
                                        ->label('Project')
                                        ->options(Project::all()->pluck('name', 'id'))
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
                        ]),
                ])
                ->columns(2),
            Group::make()
                ->schema([
                    Fieldset::make('Health')->schema([
                        Select::make('blood_group')
                            ->default(BloodGroup::DEFAULT )
                            ->enum(BloodGroup::class)
                            ->options(BloodGroup::class)
                            ->searchable(),
                    ]),
                    Fieldset::make('Department')
                        ->schema([
                            Select::make('department_id')
                                ->options(Department::all()->pluck('name', 'id'))
                                ->label('Department')
                                ->searchable()
                                ->live()
                                ->preload()
                                ->required()
                                ->columnSpanFull()
                                ->createOptionForm(Department::getForm())
                                ->createOptionModalHeading('Add Department')
                                ->createOptionUsing(function (array $data): int {
                                    return Department::create($data)->getKey();
                                }),
                            Select::make('position_id')
                                ->reactive()
                                ->visible(fn(Get $get) => $get('department_id'))
                                ->options(fn(Get $get) => Position::where('department_id', $get('department_id'))->pluck('name', 'id')->toArray())
                                ->label('Position')
                                ->searchable()
                                ->preload()
                                ->columnSpanFull()
                                ->required()
                                ->createOptionForm(Position::getForm())
                                ->createOptionModalHeading('Add Position')
                                ->createOptionUsing(function (array $data): int {
                                    return Position::create($data)->getKey();
                                }),
                            Toggle::make('hod')
                                ->inline(false)
                                ->label('Make Head of Department?')
                                ->visible(fn(Get $get) => $get('department_id')),
                        ]),
                    Fieldset::make('Payroll Details')
                        ->schema([
                            Select::make('currency_id')
                                ->options(Currency::all()->pluck('abbr', 'id'))
                                ->searchable()
                                ->preload()
                                ->required()
                                ->label('Currency')
                                ->columnSpanFull(),
                            TextInput::make('monthly_gross')
                                ->required()
                                ->columnSpanFull()
                                ->numeric(),
                            DatePicker::make('date_of_employment')
                                ->native(false)
                                ->columnSpanFull(),
                        ]),
                ])
                ->columns(1),
        ];
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
