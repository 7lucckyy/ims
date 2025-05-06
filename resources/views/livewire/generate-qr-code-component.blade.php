<x-filament-breezy::grid-section md="2" title="QR Code" description="Scan this QR code to access the information.">
    <x-filament::card>
        <div class="text-center">
            @if(@$staffId)
                @php
                    $qrCodeData = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(200)->generate($staffId));
                @endphp
                <img src="data:image/png;base64, {!! $qrCodeData !!}" alt="QR Code">

                <br>

                <a href="data:image/png;base64, {!! $qrCodeData !!}" download="qr-code.png" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded">
                    Download QR Code
                </a>
            @endif
        </div>
    </x-filament::card>
</x-filament-breezy::grid-section>
