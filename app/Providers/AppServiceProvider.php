<?php

namespace App\Providers;

use App\Livewire\SignatureComponent;
use App\Livewire\StaffDetailsComponent;
use App\Models\Role;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Jeffgreco13\FilamentBreezy\Livewire\TwoFactorAuthentication;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();

        FilamentColor::register([
            'blue' => Color::Blue,
            'fuchsia' => Color::Fuchsia,
            'cyan' => Color::Cyan,
        ]);

        app()->singleton('staff', fn () => Role::find(Role::STAFF)->users()->get());
        app()->singleton('donors', fn () => Role::find(Role::DONOR)->users()->get());

        // Profile Sort
        TwoFactorAuthentication::setSort(1);
        SignatureComponent::setSort(2);
        StaffDetailsComponent::setSort(3);
    }
}
