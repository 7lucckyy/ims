<?php

namespace App\Models;

use Cheesegrits\FilamentGoogleMaps\Fields\Map;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    use HasFactory;

    public static function getForm(): array
    {
        return [
            Map::make('location')
                ->mapControls([
                    'mapTypeControl' => true,
                    'scaleControl' => true,
                    'streetViewControl' => true,
                    'rotateControl' => true,
                    'fullscreenControl' => true,
                    'searchBoxControl' => false, // creates geocomplete field inside map
                    'zoomControl' => false,
                ])
                ->height(fn () => '400px') // map height (width is controlled by Filament options)
                ->defaultZoom(5) // default zoom level when opening form
                ->autocomplete('full_address') // field on form to use as Places geocompletion field
                ->autocompleteReverse(true) // reverse geocode marker location to autocomplete field
                ->reverseGeocode([
                    'street' => '%n %S',
                    'city' => '%L',
                    'state' => '%A1',
                    'zip' => '%z',
                ]) // reverse geocode marker location to form fields, see notes below
                ->debug() // prints reverse geocode format strings to the debug console
                ->defaultLocation([39.526610, -107.727261]) // default for new forms
                ->draggable() // allow dragging to move marker
                ->clickable(false) // allow clicking to move marker
                ->geolocate() // adds a button to request device location and set map marker accordingly
                ->geolocateLabel('Get Location') // overrides the default label for geolocate button
                ->geolocateOnLoad(true, false) // geolocate on load, second arg 'always' (default false, only for new form))
                ->layers([
                    'https://googlearchive.github.io/js-v2-samples/ggeoxml/cta.kml',
                ]) // array of KML layer URLs to add to the map
                ->geoJson('https://fgm.test/storage/AGEBS01.geojson') // GeoJSON file, URL or JSON
                ->geoJsonContainsField('geojson'), // field to capture GeoJSON polygon(s) which contain the map marker
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function getLocationAttribute(): array
    {
        return [
            'lat' => (float) $this->lat,
            'lng' => (float) $this->long,
        ];
    }

    public function setLocationAttribute(?array $location): void
    {
        if (is_array($location)) {
            $this->attributes['lat'] = $location['lat'];
            $this->attributes['long'] = $location['lng'];
            unset($this->attributes['location']);
        }
    }

    public static function getLatLngAttributes(): array
    {
        return [
            'lat' => 'lat',
            'lng' => 'long',
        ];
    }

    public static function getComputedLocation(): string
    {
        return 'location';
    }
}
