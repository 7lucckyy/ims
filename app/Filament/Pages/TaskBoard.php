<?php

namespace App\Filament\Pages;

use App\Enums\TaskStatus;
use App\Models\Department;
use App\Models\Project;
use App\Models\StaffDetail;
use App\Models\Task;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Get;
use Illuminate\Database\Eloquent\Collection;
use Mokhosh\FilamentKanban\Pages\KanbanBoard;

class TaskBoard extends KanbanBoard
{
    protected static string $model = Task::class;

    protected static string $statusEnum = TaskStatus::class;

    public $department = null;

    public $project = null;

    public $staff = null;

    protected function getEditModalFormSchema(string|int|null $recordId): array
    {
        return Task::getForm();
    }

    protected function records(): Collection
    {
        return Task::query()
            ->when($this->department, function ($query) {
                $query->where('department_id', $this->department);
            })
            ->when($this->project, function ($query) {
                $query->where('project_id', $this->project);
            })
            ->when($this->staff, function ($query) {
                $query->where('user_id', $this->staff);
            })
            ->get();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('filter')
                ->icon('heroicon-o-funnel')
                ->model(Task::class)
                ->form([
                    Select::make('project')
                        ->options(Project::all()->pluck('name', 'id'))
                        ->searchable()
                        ->preload(),
                    Select::make('department')
                        ->options(Department::all()->pluck('name', 'id'))
                        ->searchable()
                        ->live()
                        ->preload(),
                    Select::make('staff')
                        ->options(function (Get $get) {
                            if ($get('department')) {

                                $users = StaffDetail::where('department_id', $get('department'))->pluck('user_id');

                                return User::whereIn('id', $users)->get()->pluck('name', 'id');
                            } else {
                                return app('staff')->pluck('name', 'id');
                            }
                        })
                        ->searchable()
                        ->preload(),
                ])
                ->action(function (array $data): void {
                    $this->department = $data['department'];
                    $this->project = $data['project'];
                    $this->staff = $data['staff'];
                })
                ->modalSubmitActionLabel('Apply'),
        ];
    }
}
