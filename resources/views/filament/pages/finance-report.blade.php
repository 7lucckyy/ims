<x-filament::page>
    <x-filament::form>
        {{ $this->form }}
    </x-filament::form>

    @if($project_id)
        <div class="p-6 bg-white rounded-lg shadow">
            {{ $this->infolist('default') }}
        </div>
    @else
        <div class="p-6 text-gray-500">
            Select a project to view financial report
        </div>
    @endif
</x-filament::page>
