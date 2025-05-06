<?php

namespace App\Filament\Widgets;

use App\Models\Sector;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Widgets\Widget;

class Filters extends Widget implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.widgets.filters';

    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    public ?array $data = [
        'project' => null,
        'sector' => null,
    ];

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Grid::make()
                    ->schema([
                        Select::make('sector')
                            ->options(Sector::all()->pluck('name', 'id'))
                            ->live()
                            ->searchable()
                            ->preload(),
                        Select::make('project')
                            ->visible(fn (Get $get) => $get('sector'))
                            ->live()
                            ->options(fn (Get $get) => Sector::find($get('sector'))->projects()->get()->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->afterStateUpdated(fn (?string $state) => $this->dispatch('updateProject', $state)),
                    ]),
            ]);
    }
}
