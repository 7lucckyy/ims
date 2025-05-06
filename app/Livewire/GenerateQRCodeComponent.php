<?php

namespace App\Livewire;

use Filament\Facades\Filament;
use Livewire\Component;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Jeffgreco13\FilamentBreezy\Livewire\MyProfileComponent;

class GenerateQRCodeComponent extends MyProfileComponent
{
    public ?string $qrCodePath = null;

    public ?string $staffId = null;

    public function mount(): void
    {
        $user = Filament::getCurrentPanel()->auth()->user();

        if(!$user->staffId){
            $user->staffId = uuid_create();
            $user->save();
        }

        if ($user && $user->staffId) {
            $this->staffId = $user->staffId;
            $this->generateQrCode($user->staffId);
        }
    }

    private function generateQrCode(string $staffId): void
    {
        $fileName = "qr-codes/{$staffId}.png";

        // Ensure storage directory exists
        Storage::disk('public')->makeDirectory('qr-codes');

        // Generate and save QR code if it doesn't exist
        if (!Storage::disk('public')->exists($fileName)) {
            $qrCode = QrCode::size(300)->format('png')->generate($staffId);
            Storage::disk('public')->put($fileName, $qrCode);
        }

        // Use asset() instead of Storage::url()
        $this->qrCodePath = asset('storage/' . $fileName);
    }

    public function render()
    {
        return view('livewire.generate-qr-code-component', [
            'qrCodePath' => $this->qrCodePath,
            'staffId' => $this->staffId,
        ]);
    }
        
}
