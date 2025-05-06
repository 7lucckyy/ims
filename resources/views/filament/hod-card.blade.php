<div class="relative transform overflow-hidden rounded-lg bg-white px-4 pt-5 pb-4 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-sm sm:p-6">
    <div>
        <div class="mx-auto flex size-12 items-center justify-center rounded-full bg-green-100">
            <img
                src="{{ $record->hod->getFilamentAvatarUrl() }}"
                alt=""
                class="w-10 h-10 rounded-full bg-gray-100">
        </div>
        <div class="mt-3 text-center sm:mt-5">
            <h3 class="text-base font-semibold text-gray-900" id="modal-title">{{ $record->hod->name }}</h3>
            <div class="mt-2">
                <p class="text-sm text-gray-500">{{ $record->hod->email }}</p>
            </div>
        </div>
    </div>
</div>
