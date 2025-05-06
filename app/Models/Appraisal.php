<?php

namespace App\Models;

use App\Enums\AppraisalCycle;
use App\Enums\AppraisalMethod;
use App\Enums\EvaluationCriteria;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Get;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appraisal extends Model
{
    use HasFactory;

    protected $fillable = ['id'];

    protected function casts(): array
    {
        return [
            'cycle' => AppraisalCycle::class,
            'criteria' => EvaluationCriteria::class,
            'method' => AppraisalMethod::class,
            'evaluation_criteria' => 'json',
            'staff_input' => 'json',
        ];
    }

    public static function getForm(): array
    {
        return [
            Select::make('staff_id')
                ->relationship('staff', 'name')
                ->options(app('staff')->pluck('name', 'id'))
                ->searchable()
                ->columnSpanFull()
                ->live()
                ->preload()
                ->required(),
            Grid::make(2)
                ->visible(fn (Get $get) => $get('staff_id'))
                ->schema([
                    Select::make('cycle')
                        ->default(AppraisalCycle::DEFAULT)
                        ->options(AppraisalCycle::class)
                        ->enum(AppraisalCycle::class)
                        ->live()
                        ->searchable()
                        ->required(),
                    Select::make('method')
                        ->default(AppraisalMethod::DEFAULT)
                        ->options(AppraisalMethod::class)
                        ->enum(AppraisalMethod::class)
                        ->live()
                        ->searchable()
                        ->required(),
                ]),
            Select::make('project_id')
                ->visible(fn (Get $get) => $get('cycle') && $get('cycle') === AppraisalCycle::ProjectBased)
                ->relationship('project', 'name')
                ->options(fn (Get $get) => User::find($get('staff_id'))->projects()->get()->pluck('name', 'id'))
                ->searchable()
                ->reactive()
                ->columnSpanFull()
                ->preload(),
            Fieldset::make('Evaluation Criteria')
                ->visible(fn (Get $get) => $get('staff_id'))
                ->schema([
                    Repeater::make('evaluation_criteria')
                        ->addActionLabel('Add Evaluation Criteria')
                        ->columns(2)
                        ->columnSpanFull()
                        ->hiddenLabel()
                        ->schema([
                            Select::make('criteria')
                                ->options(EvaluationCriteria::class)
                                ->enum(EvaluationCriteria::class)
                                ->searchable()
                                ->required()
                                ->disableOptionsWhenSelectedInSiblingRepeaterItems(),
                            Textarea::make('remarks')
                                ->required(),
                        ]),
                ]),
            Fieldset::make('Staff Input')
                ->visible(fn (Get $get) => $get('staff_id'))
                ->hidden(fn (Get $get) => $get('method') && $get('method') === AppraisalMethod::SelfAppraisal)
                ->schema([
                    Repeater::make('staff_input')
                        ->addActionLabel('Add Staff Input')
                        ->columns(2)
                        ->columnSpanFull()
                        ->hiddenLabel()
                        ->schema([
                            Select::make('staff')
                                ->options(app('staff')->pluck('name', 'id'))
                                ->searchable()
                                ->preload()
                                ->required()
                                ->disableOptionsWhenSelectedInSiblingRepeaterItems(),
                            Textarea::make('remarks')
                                ->required(),
                        ]),

                ]),
            RichEditor::make('feedback')
                ->visible(fn (Get $get) => $get('staff_id'))
                ->columnSpanFull(),
            RichEditor::make('discussion')
                ->visible(fn (Get $get) => $get('staff_id'))
                ->columnSpanFull(),
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}
