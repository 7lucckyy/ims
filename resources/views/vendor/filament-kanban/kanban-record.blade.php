<div id="{{ $record->getKey() }}" wire:click="recordClicked('{{ $record->getKey() }}', {{ @json_encode($record) }})"
    class="px-4 py-2 font-medium text-gray-600 bg-white rounded-lg record dark:bg-gray-700 cursor-grab dark:text-gray-200"
    @if($record->timestamps && now()->diffInSeconds($record->{$record::UPDATED_AT}) < 3) x-data x-init="
        $el.classList.add('animate-pulse-twice', 'bg-primary-100', 'dark:bg-primary-800')
        $el.classList.remove('bg-white', 'dark:bg-gray-700')
        setTimeout(() => {
            $el.classList.remove('bg-primary-100', 'dark:bg-primary-800')
            $el.classList.add('bg-white', 'dark:bg-gray-700')
        }, 3000)
    " @endif>
    {{ $record->{static::$recordTitleAttribute} }}
    <br />

    <div class="pl-2 my-2 text-sm text-gray-400 truncate border-l-2">
        {!! $record->description !!}
    </div>
    <div class="flex justify-between my-2">

        <div class="relative flex items-center mt-2 gap-x-4">
            <img src="{{ $record->user->getFilamentAvatarUrl() }}" alt="" class="w-10 h-10 bg-gray-100 rounded-full">
            <div class="text-sm/6">
                <p class="font-semibold text-white">
                    <a href="">
                        <span class="absolute inset-0"></span>
                        {{ $record->user->name }}
                    </a>
                </p>
                <p class="text-sky-500">
                    {{ $record->user->staffDetail->department->name }}
                </p>
                <p class="text-emerald-500">{{ $record->project->name }}</p>
            </div>
        </div>

        <div class="items-center mt-8 text-xs rounded-full">
            {{ $record->deadline }}
        </div>
    </div>

    <div class="relative mt-2">
        <div class="absolute h-1 rounded-full bg-sky-600"
            style="width: {{ App\Models\ProjectUser::where('user_id', $record->user->id)->where('project_id', $record->project->id)->first()->project_involvement_percentage ?? 0 }}%">
        </div>
    </div>
</div>