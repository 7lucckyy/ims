<?php

namespace App\Filament\Meal\Resources\ProjectResource\Pages;

use App\Filament\Meal\Resources\ProjectResource;
use App\Filament\Staff\Resources\TaskResource;
use App\Models\Role;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Actions\Action as ActionsAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\Alignment;

class ViewProject extends ViewRecord
{
    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                Action::make('task')
                    ->label('Add Task')
                    ->icon('heroicon-o-briefcase')
                    ->modalIcon('heroicon-o-briefcase')
                    ->modalAlignment(Alignment::Center)
                    ->modalSubmitActionLabel('Add Project Task')
                    ->color('blue')
                    ->form([
                        Grid::make(2)
                            ->schema([
                                Select::make('user_id')
                                    ->options(fn($record) => $record->users()->get()->pluck('name', 'id'))
                                    ->searchable()
                                    ->preload()
                                    ->label('Staff')
                                    ->required(),
                                Select::make('activity_id')
                                    ->options(fn($record) => $record->activities()->get()->pluck('name', 'id'))
                                    ->searchable()
                                    ->preload()
                                    ->label('Activity')
                                    ->required(),
                            ]),
                        Grid::make(2)
                            ->schema([
                                TextInput::make('title')
                                    ->required(),
                                DatePicker::make('deadline')
                                    ->native(false)
                                    ->required(),
                            ]),
                        RichEditor::make('description')
                            ->columnSpanFull(),
                    ])
                    ->action(function (array $data, $record) {

                        $record->tasks()->create([
                            'user_id' => $userId = $data['user_id'],
                            'activity_id' => $data['activity_id'],
                            'title' => $data['title'],
                            'deadline' => $data['deadline'],
                            'description' => $data['description'],
                        ]);

                        $user = User::find($userId);

                        $user->notify(

                            Notification::make()
                                ->title('New Task')
                                ->body('Task for project ' . $record->code)
                                ->icon('heroicon-o-briefcase')
                                ->iconColor('info')
                                ->actions([
                                    ActionsAction::make('view')
                                        ->markAsRead()
                                        ->color('info')
                                        ->icon('heroicon-o-eye')
                                        ->url(TaskResource::getUrl('view', ['record' => $record->id], panel: 'staff')),
                                ])
                                ->toDatabase()

                        );

                    }),
                Action::make('staff')
                    ->label('Assign to staff')
                    ->color('fuchsia')
                    ->icon('heroicon-o-user-group')
                    ->modalIcon('heroicon-o-user-group')
                    ->modalAlignment(Alignment::Center)
                    ->modalSubmitActionLabel('Assign Staff to Project')
                    ->form([
                        Repeater::make('staff')
                            ->addActionLabel('Add Staff')
                            ->columns(2)
                            ->hiddenLabel()
                            ->schema([
                                Select::make('user_id')
                                    ->options(function ($record) {
                                        $inProject = $record->users()->pluck('user_id');

                                        return User::role(Role::STAFF)
                                            ->whereNotIn('id', $inProject)
                                            ->pluck('name', 'id');
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->label('Staff')
                                    ->required()
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems(),
                                TextInput::make('project_involvement_percentage')
                                    ->label('Project involvement')
                                    ->suffix('%')
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->required(),
                            ]),
                    ])
                    ->action(function (array $data, $record) {

                        $record->users()->attach($data['staff']);

                    }),
            ]),
        ];
    }
}
