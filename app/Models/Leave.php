<?php

namespace App\Models;

use App\Enums\LeaveStatus;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Leave extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'approval' => LeaveStatus::class,
        ];
    }

    public static function getForm(): array
    {
        return [
            Select::make('user_id')
                ->label('Staff')
                ->options(User::role(Role::STAFF)->get()->pluck('name', 'id'))
                ->hidden(fn () => Filament::getCurrentPanel()->getId() == 'staff')
                ->searchable()
                ->preload(),
            TextInput::make('period_of_leave')
                ->required(),
            RichEditor::make('reason')
                ->required()
                ->columnSpanFull(),
            DatePicker::make('start_date')
                ->required(),
            DatePicker::make('aspected_resumption_date')
                ->required(),
            FileUpload::make('document')
                ->columnSpanFull(),
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function document(): MorphOne
    {
        return $this->morphOne(Document::class, 'modelable');
    }

    public function approve(): void
    {
        $this->update([
            'approval' => LeaveStatus::Approved,
        ]);

        $this->save();
    }

    public function reject(): void
    {
        $this->update([
            'approval' => LeaveStatus::Rejected,
        ]);

        $this->save();
    }
}
