<x-filament-panels::page>
    <h2 class="text-lg font-semibold" @style('margin-bottom:-25px')>Welcome {{auth()->user()->name}}</h2>
    <p class="text-sm text-gray-600 font-semibold">{{\Carbon\Carbon::now()->toFormattedDayDateString()}}</p>
</x-filament-panels::page>
