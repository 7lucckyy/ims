<?php

namespace App\Models;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use HasFactory;

    protected $casts = [
        'items' => 'json', // Cast 'items' as JSON
    ];

    // Relationships
    public function budgetTrench(): BelongsTo
    {
        return $this->belongsTo(BudgetTrench::class, 'budget_trench_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class);
    }

    public function budget_details(): BelongsTo
    {
        return $this->belongsTo(BudgetDetail::class, 'budget_details_id');
    }

    public static function getForm(): array
    {
        return [
            Section::make('Budget Details')
                ->columns(3)
                ->schema([
                    Select::make('budget_trench_id')
                        ->label('Budget Trench')
                        ->relationship('budgetTrench', 'code')
                        ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->code} ({$record->status})")
                        ->searchable()
                        ->preload()
                        ->required()
                        ->live()
                        ->afterStateUpdated(function ($state, Set $set) {
                            $budgetTrench = BudgetTrench::find($state);
                            $set('name', $budgetTrench->project->name);
                            // Set the trench amount
                            $set('trench_amount', $budgetTrench->amount); // Replace 'trench_amount' with the correct field name
                        }),

                    // Automatically display Project Name
                    TextInput::make('name')
                        ->label('Project')
                        ->readOnly(),

                    // Display Trench Amount
                    TextInput::make('trench_amount')
                        ->label('Trench Amount')
                        ->numeric()
                        ->readOnly(),

                    Select::make('budget_details_id')
                        ->label('Budget Line')
                        ->relationship('budget_details', titleAttribute: 'line')
                        ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->line}")
                        ->searchable()
                        ->preload()
                        ->required(),
                    // Payee and Transaction Date
                    Select::make('vendor_id')
                        ->label('Payee')
                        ->options(Vendor::all()->pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->required()
                        ->createOptionForm(Vendor::getForm())
                        ->createOptionModalHeading('Add Vendor')
                        ->createOptionUsing(function (array $data): int {
                            $vendor = Vendor::create($data);

                            return $vendor->getKey();
                        }),
                    TextInput::make('ref_number')
                        ->label('Ref Number')
                        ->required(),
                    DatePicker::make('transaction_date')
                        ->label('Transaction Date')
                        ->required(),

                    // File Uploads
                    FileUpload::make('memo')
                        ->label('Memo (PDF)')
                        ->disk('public') // Ensure you configure the disk in `filesystems.php`
                        ->directory('memos') // Specify upload directory
                        ->acceptedFileTypes(['application/pdf'])
                        ->maxSize(2048), // Max file size in KB

                    FileUpload::make('attachment')
                        ->label('Attachment')
                        ->disk('public')
                        ->directory('attachments')
                        ->maxSize(4096), // Max file size in KB
                ]),

            // Repeater for expense items
            Repeater::make('items')
                ->label('Expense Items')
                ->schema([
                    Select::make('category_id')
                        ->relationship('category', 'name')
                        ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->name}")
                        ->searchable()
                        ->preload()
                        ->required(),

                    TextInput::make('description')
                        ->label('Description')
                        ->required(),

                    TextInput::make('amount')
                        ->label('Amount')
                        ->numeric()
                        ->required(),
                ])
                ->columns(3)
                ->createItemButtonLabel('Add Expense Item')
                ->columnSpanFull(),
        ];
    }
}
