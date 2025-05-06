<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Staff\Resources\TaskResource;
use App\Models\Activity;
use App\Models\Project;
use App\Models\Sector;
use App\Models\User;
use Cheesegrits\FilamentGoogleMaps\Infolists\MapEntry;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Actions\Action as ActionsAction;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'MEAL';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(components: Project::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('budget_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('duration')
                    ->suffix(' months')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('sector_id')
                    ->label('Sector')
                    ->options(Sector::all()->pluck('name', 'id')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        $projectId = $infolist->record->id;

        return $infolist
            ->schema([
                Section::make('Primary Information')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('code'),
                        TextEntry::make('duration')
                            ->suffix(' months'),
                        TextEntry::make('budget')
                            ->money(fn ($record) => $record->currency->abbr),
                        TextEntry::make('budget_code'),
                        TextEntry::make('sectors.name'),
                    ]),
                Tabs::make('Tabs')
                    ->columnSpanFull()
                    ->tabs([
                        Tabs\Tab::make('Locations')
                            ->badge(fn ($record) => $record->locations()->count())
                            ->schema([
                                RepeatableEntry::make('locations')
                                    ->columns(2)
                                    ->columnSpanFull()
                                    ->hiddenLabel()
                                    ->grid(2)
                                    ->schema([
                                        MapEntry::make('location')
                                            ->columnSpanFull()
                                            ->hiddenLabel(),
                                    ]),
                            ]),
                        Tabs\Tab::make('Staff')
                            ->badge(fn ($record) => $record->users()->count())
                            ->schema([
                                RepeatableEntry::make('users')
                                    ->hiddenLabel()
                                    ->columns(3)
                                    ->schema([
                                        TextEntry::make('name'),
                                        TextEntry::make('email')
                                            ->copyable(),
                                        TextEntry::make('project_involvement')
                                            ->getStateUsing(fn ($record) => $record->pivot->project_involvement_percentage.'%')
                                            ->suffixActions([
                                                Action::make('view')
                                                    ->icon('heroicon-o-eye')
                                                    ->color('success')
                                                    ->tooltip('View Staff')
                                                    ->url(fn ($record) => StaffResource::getUrl('view', ['record' => $record->id])),
                                                Action::make('task')
                                                    ->icon('heroicon-o-briefcase')
                                                    ->tooltip('Assign Task')
                                                    ->color('info')
                                                    ->form([
                                                        Grid::make(2)
                                                            ->schema([
                                                                TextInput::make('title')
                                                                    ->required(),
                                                                DatePicker::make('deadline')
                                                                    ->required()
                                                                    ->native(false),
                                                            ]),
                                                        Select::make('activity_id')
                                                            ->searchable()
                                                            ->preload()
                                                            ->reactive()
                                                            ->options(fn () => Activity::where('project_id', $projectId)
                                                                ->get()
                                                                ->pluck('name', 'id')
                                                                ->toArray()
                                                            )
                                                            ->required()
                                                            ->label('Activity'),
                                                        RichEditor::make('description')
                                                            ->columnSpanFull(),
                                                    ])
                                                    ->action(function (array $data, $record) {

                                                        $task = $record->tasks()->create([
                                                            'project_id' => $record->pivot->project_id,
                                                            'deadline' => $data['deadline'],
                                                            'activity_id' => $data['activity_id'],
                                                            'description' => $data['description'],
                                                            'title' => $data['title'],
                                                        ]);

                                                        // Notify recipient
                                                        $recipient = User::find($record->pivot->user_id);

                                                        $recipient->notify(
                                                            Notification::make()
                                                                ->title('New Task')
                                                                ->body('Task for project '.$record->code)
                                                                ->icon('heroicon-o-briefcase')
                                                                ->iconColor('info')
                                                                ->actions([
                                                                    ActionsAction::make('view')
                                                                        ->markAsRead()
                                                                        ->color('info')
                                                                        ->icon('heroicon-o-eye')
                                                                        ->url(TaskResource::getUrl('view', ['record' => $task->id], panel: 'staff')),
                                                                ])
                                                                ->toDatabase()

                                                        );

                                                    }),
                                            ]),
                                    ]),
                            ]),
                        Tabs\Tab::make('Tasks')
                            ->badge(fn ($record) => $record->tasks()->count())
                            ->schema([
                                RepeatableEntry::make('tasks')
                                    ->columns(4)
                                    ->hiddenLabel()
                                    ->schema([
                                        TextEntry::make('user.name')
                                            ->label('Staff'),
                                        TextEntry::make('title'),
                                        TextEntry::make('deadline')
                                            ->date(),
                                        TextEntry::make('status'),
                                    ]),
                            ]),
                        Tabs\Tab::make('Documents')
                            ->badge(fn ($record) => $record->documents()->count())
                            ->schema([
                                RepeatableEntry::make('documents')
                                    ->columnSpanFull()
                                    ->hiddenLabel()
                                    ->schema([
                                        TextEntry::make('type')
                                            ->label('Type'),
                                        TextEntry::make('file')
                                            ->label('Document')
                                            ->formatStateUsing(fn () => 'Download Document')
                                            ->url(fn ($record) => Storage::url($record->file), true)
                                            ->badge()
                                            ->color(Color::Blue),
                                    ])
                                    ->columns()
                                    ->grid(2),
                            ]),
                        Tabs\Tab::make('Indicators')
                            ->badge(fn ($record) => $record->indicators()->count())
                            ->schema([
                                RepeatableEntry::make('indicators')
                                    ->columns(3)
                                    ->hiddenLabel()
                                    ->schema([
                                        TextEntry::make('code'),
                                        TextEntry::make('name'),
                                        TextEntry::make('target')
                                            ->suffixActions([
                                                Action::make('view')
                                                    ->icon('heroicon-o-eye')
                                                    ->url(fn ($record) => IndicatorResource::getUrl('view', ['record' => $record->id]))
                                                    ->tooltip('View Indicator'),
                                            ]),
                                    ]),
                            ]),
                        Tabs\Tab::make('Activities')
                            ->badge(fn ($record) => $record->activities()->count())
                            ->schema([
                                RepeatableEntry::make('activities')
                                    ->columns(3)
                                    ->hiddenLabel()
                                    ->schema([
                                        TextEntry::make('code'),
                                        TextEntry::make('name'),
                                        TextEntry::make('indicator.code')
                                            ->suffixActions([
                                                Action::make('view')
                                                    ->icon('heroicon-o-eye')
                                                    ->url(fn ($record) => ActivityResource::getUrl('view', ['record' => $record->id]))
                                                    ->tooltip('View Activity'),
                                            ]),
                                    ]),

                            ]),
                        Tabs\Tab::make('Reports')
                            ->badge(fn ($record) => $record->reports()->count())
                            ->schema([
                                RepeatableEntry::make('reports')
                                    ->columns(3)
                                    ->hiddenLabel()
                                    ->schema([
                                        TextEntry::make('month'),
                                        TextEntry::make('location.name'),
                                        TextEntry::make('activity.name')
                                            ->suffixActions([
                                                Action::make('view')
                                                    ->icon('heroicon-o-eye')
                                                    ->url(fn ($record) => ReportResource::getUrl('view', ['record' => $record->id]))
                                                    ->tooltip('View Report'),
                                            ]),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'view' => Pages\ViewProject::route('/{record}/view'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }

    public static function getGlobalSearchResultUrl(Model $record): string
    {
        return ProjectResource::getUrl('view', ['record' => $record]);
    }
}
