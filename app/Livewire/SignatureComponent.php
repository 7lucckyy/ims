<?php

namespace App\Livewire;

use Filament\Facades\Filament;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Jeffgreco13\FilamentBreezy\Livewire\MyProfileComponent;
use Saade\FilamentAutograph\Forms\Components\SignaturePad;

class SignatureComponent extends MyProfileComponent
{
    protected string $view = 'livewire.signature-component';

    public array $only = ['signature'];

    public array $data;

    public $user;

    public $userClass;

    public function mount()
    {
        $this->user = Filament::getCurrentPanel()->auth()->user();
        $this->userClass = get_class($this->user);

        $this->form->fill($this->user->only($this->only));
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                SignaturePad::make('signature')
                    ->backgroundColorOnDark('#fafafa')
                    ->penColorOnDark('#262626'),
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $data = collect($this->form->getState())->only($this->only)->all();
        $this->user->update($data);
        Notification::make()
            ->success()
            ->title(__('Signature updated successfully'))
            ->send();
    }
}
