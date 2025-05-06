<?php

namespace App\Models;

use App\Enums\ProjectStatus;
use Cheesegrits\FilamentGoogleMaps\Fields\Map;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Project extends Model implements Sortable
{
    use HasFactory;
    use SortableTrait;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'status' => ProjectStatus::class,
        ];
    }

    public $sortable = [
        'order_column_name' => 'order_column',
        'sort_when_creating' => true,
    ];

    public static function getForm(): array
    {
        return [
            Wizard::make([
                Wizard\Step::make('Project Details')
                    ->columns(12)
                    ->icon('heroicon-o-map-pin')
                    ->schema([
                        Group::make()
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('code')
                                            ->label('Project code')
                                            ->required(),
                                        TextInput::make('name')
                                            ->required(),
                                    ]),
                                Repeater::make('locations')
                                    ->schema([
                                        TextInput::make('name')
                                            ->required(),
                                        Map::make('location')
                                            ->mapControls([
                                                'mapTypeControl' => true,
                                                'scaleControl' => true,
                                                'streetViewControl' => true,
                                                'rotateControl' => true,
                                                'fullscreenControl' => true,
                                                'searchBoxControl' => true,
                                                'zoomControl' => false,
                                            ])
                                            ->height(fn () => '400px')
                                            ->defaultZoom(5)
                                            ->autocomplete('full_address')
                                            ->autocompleteReverse(true)
                                            ->reverseGeocode([
                                                'street' => '%n %S',
                                                'city' => '%L',
                                                'state' => '%A1',
                                                'zip' => '%z',
                                            ])
                                            ->debug()
                                            ->draggable()
                                            ->clickable(true)
                                            ->geolocate()
                                            ->geolocateLabel('Get Location')
                                            ->geolocateOnLoad(true, false)
                                            ->layers([
                                                'https://googlearchive.github.io/js-v2-samples/ggeoxml/cta.kml',
                                            ])
                                            ->geoJson('https://fgm.test/storage/AGEBS01.geojson')
                                            ->geoJsonContainsField('geojson'),
                                    ])
                                    ->cloneable()
                                    ->addActionLabel('Add Location')
                                    ->columnSpanFull(),
                            ])
                            ->columnSpan(8),
                        Group::make()
                            ->schema([
                                Fieldset::make('Sector & Budget')
                                    ->columns(1)
                                    ->schema([
                                        CheckboxList::make('sectors')
                                            ->options(Sector::all()->pluck('name', 'id'))
                                            ->label('Sectors')
                                            ->required(),
                                        TextInput::make('duration')
                                            ->numeric()
                                            ->hint('in Months')
                                            ->required(),
                                        TextInput::make('budget_code')

                                            ->label('Budget Code')
                                            ->required(),
                                        Select::make('currency_id')
                                            ->options(Currency::all()->pluck('abbr', 'id'))
                                            ->label('Currency')
                                            ->required()
                                            ->searchable()
                                            ->preload(),
                                        TextInput::make('budget')
                                            ->numeric()
                                            ->label('Budget Amount')
                                            ->required(),
                                    ]),
                            ])
                            ->columnSpan(4),
                    ]),
                Wizard\Step::make('Documents')
                    ->icon('heroicon-o-document-duplicate')
                    ->schema([
                        FileUpload::make('budget_file')
                            ->hint('Upload spreadsheet only'),
                        FileUpload::make('attachments')
                            ->acceptedFileTypes(['application/pdf'])
                            ->multiple()
                            ->hint('Upload pdf only'),
                    ]),
            ])
                ->columnSpanFull()
                ->skippable(),
        ];
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function sectors(): BelongsToMany
    {
        return $this->belongsToMany(Sector::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->using(ProjectUser::class)
            ->withPivot('project_involvement_percentage')
            ->withTimestamps();
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    public function beneficiary(): HasMany
    {
        return $this->hasMany(Beneficiary::class);
    }

    public function budgetDetails(): HasMany
    {
        return $this->hasMany(BudgetDetail::class);
    }

    public function budgetTrenches(): HasMany
    {
        return $this->hasMany(BudgetTrench::class);
    }

    public function competitiveBids(): HasMany
    {
        return $this->hasMany(CompetitiveBid::class);
    }

    public function indicators(): HasMany
    {
        return $this->hasMany(Indicator::class);
    }

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'modelable');
    }
}
