<?php

namespace App\Filament\Admin\Resources;

use App\Enums\EventStatus;
use App\Filament\Admin\Resources\EventResource\Pages;
use App\Filament\Admin\Resources\EventResource\RelationManagers;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

// Import yang benar untuk Filament v3
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Event')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('status')
                            ->options(EventStatus::class)
                            ->required(),
                        Forms\Components\RichEditor::make('description')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Poster')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('event_posters')
                            ->collection('event_posters')
                            ->label('Upload Poster')
                            ->image()
                            ->responsiveImages()
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Waktu dan Lokasi')
                    ->schema([
                        Forms\Components\DateTimePicker::make('start_date')
                            ->label('Tanggal Mulai')
                            ->required()
                            ->minDate(now())
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                // Clear end_date if start_date is changed to avoid conflicts
                                if ($state) {
                                    $set('end_date', null);
                                }
                            }),
                        Forms\Components\DateTimePicker::make('end_date')
                            ->label('Tanggal Selesai')
                            ->required()
                            ->minDate(fn ($get) => $get('start_date') ?: now())
                            ->afterOrEqual('start_date')
                            ->live(),
                        Forms\Components\TextInput::make('location')
                            ->label('Lokasi')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Alamat venue atau link online'),
                        Forms\Components\Toggle::make('is_online')
                            ->label('Event Online?')
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $set('location', ''); // Clear location for online events
                                }
                            }),
                        Forms\Components\Toggle::make('has_numbered_seats')
                            ->label('Memiliki Kursi Bernomor?')
                            ->reactive()
                            ->helperText('Aktifkan jika event memiliki sistem kursi bernomor'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('event_posters')
                    ->label('Poster')
                    ->collection('event_posters'),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge(),
                Tables\Columns\TextColumn::make('start_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\TicketsRelationManager::class,
            RelationManagers\SeatsRelationManager::class,
        ];
    }

    protected static function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id() ?? 1; // Fallback to user ID 1 if not authenticated
    
        return $data;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
