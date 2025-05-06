<?php

namespace App\Livewire;

use App\Enums\BloodGroup;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Jeffgreco13\FilamentBreezy\Livewire\MyProfileComponent;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;

class StaffDetailsComponent extends MyProfileComponent
{
    protected string $view = 'livewire.staff-details-component';

    public array $only = [
        'dob',
        'phone_number',
        'emergency_contact_number',
        'blood_group',
        'address',
    ];

    public array $data;

    public $user;

    public $userClass;

    public function mount(): void
    {
        $this->user = Filament::getCurrentPanel()->auth()->user();
        $this->userClass = get_class($this->user);

        $this->form->fill($this->user->staffDetail->only($this->only));
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('dob')
                    ->label('Date of Birth')
                    ->native(false),
                PhoneInput::make('phone_number')
                    ->defaultCountry('NG')
                    ->displayNumberFormat(PhoneInputNumberType::INTERNATIONAL)
                    ->focusNumberFormat(PhoneInputNumberType::INTERNATIONAL),
                PhoneInput::make('emergency_contact_number')
                    ->defaultCountry('NG')
                    ->displayNumberFormat(PhoneInputNumberType::INTERNATIONAL)
                    ->focusNumberFormat(PhoneInputNumberType::INTERNATIONAL),
                Select::make('blood_group')
                    ->default(BloodGroup::DEFAULT)
                    ->enum(BloodGroup::class)
                    ->options(BloodGroup::class)
                    ->searchable(),
                RichEditor::make('address')
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $data = collect($this->form->getState())->only($this->only)->all();

        $this->user->staffDetail->update($data);

        Notification::make()
            ->success()
            ->title(__('Details updated successfully'))
            ->send();
    }
}
