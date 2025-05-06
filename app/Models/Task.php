<?php

namespace App\Models;

use App\Enums\TaskStatus;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Task extends Model implements Sortable
{
    use HasFactory;
    use SortableTrait;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'status' => TaskStatus::class,
        ];
    }

    public $sortable = [
        'order_column_name' => 'order_column',
        'sort_when_creating' => true,
    ];

    public static function getForm(): array
    {
        return [
            Grid::make(2)
                ->schema([
                    Select::make('project_id')
                        ->options(Project::all()->pluck('name', 'id'))
                        ->label('Project')
                        ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->code} - {$record->name}")
                        ->searchable(['code', 'name'])
                        ->live()
                        ->preload()
                        ->required(),
                    Select::make('activity_id')
                        ->visible(fn (Get $get) => $get('project_id'))
                        ->searchable()
                        ->preload()
                        ->reactive()
                        ->options(fn (Get $get) => Activity::where('project_id', $get('project_id'))->get()->pluck('name', 'id')->toArray())
                        ->required()
                        ->label('Activity'),
                ]),
            Select::make('user_id')
                ->visible(fn (Get $get) => $get('project_id'))
                ->options(fn (Get $get) => Project::find($get('project_id'))->users()->get()->pluck('name', 'id'))
                ->hidden(fn () => Filament::getCurrentPanel()->getId() == 'staff')
                ->label('Staff')
                ->searchable()
                ->columnSpanFull()
                ->preload()
                ->required()
                ->live(),
            TextInput::make('title')
                ->required()
                ->maxLength(255),
            DatePicker::make('deadline')
                ->required()
                ->native(false),
            RichEditor::make('description')
                ->columnSpanFull(),
        ];
    }

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
