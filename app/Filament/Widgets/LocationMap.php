<?php

namespace App\Filament\Widgets;

use App\Models\Location;
use App\Models\Project;
use Cheesegrits\FilamentGoogleMaps\Widgets\MapWidget;
use Livewire\Attributes\On;

class LocationMap extends MapWidget
{
    protected static ?string $heading = 'Locations';

    protected static ?int $sort = 5;

    protected static ?string $pollingInterval = null;

    protected int|string|array $columnSpan = 'full';

    protected static ?bool $clustering = true;

    protected static ?bool $fitToBounds = true;

    protected static ?int $zoom = 12;

    public ?Project $project = null;

    #[On('updateProject')]
    public function updateProject(int $project): void
    {
        $this->project = Project::find($project);
        $this->getData();
    }

    protected function getData(): array
    {
        /**
         * You can use whatever query you want here, as long as it produces a set of records with your
         * lat and lng fields in them.
         */
        $locations = Location::query()
            ->when($this->project, function ($query) {
                $query->where('project_id', $this->project->id);
            })
            ->get();

        $data = [];

        foreach ($locations as $location) {
            /**
             * Each element in the returned data must be an array
             * containing a 'location' array of 'lat' and 'lng',
             * and a 'label' string (optional but recommended by Google
             * for accessibility.
             *
             * You should also include an 'id' attribute for internal use by this plugin
             */
            $data[] = [
                'location' => [
                    'lat' => $location->lat ? round(floatval($location->lat), static::$precision) : 0,
                    'lng' => $location->long ? round(floatval($location->long), static::$precision) : 0,
                ],

                'label' => $location->lat.','.$location->long,

                'id' => $location->getKey(),

                /**
                 * Optionally you can provide custom icons for the map markers,
                 * either as scalable SVG's, or PNG, which doesn't support scaling.
                 * If you don't provide icons, the map will use the standard Google marker pin.
                 */
                'icon' => [
                    'url' => url('images/dealership.svg'),
                    'type' => 'svg',
                    'scale' => [35, 35],
                ],
            ];
        }

        return $data;
    }
}
