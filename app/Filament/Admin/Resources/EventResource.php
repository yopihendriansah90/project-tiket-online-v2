<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\EventResource\Pages;
use App\Filament\Admin\Resources\EventResource\RelationManagers;
use App\Models\Event;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Forms\Get;


class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $modelLabel = 'Event';
    protected static ?string $pluralModelLabel = 'Event';
    protected static ?string $navigationGroup = 'Manajemen Event';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Tabs')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Informasi Utama')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Forms\Components\Section::make('Detail Event')
                                    ->schema([
                                        Forms\Components\TextInput::make('title')
                                            ->label('Nama Event')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\RichEditor::make('description')
                                            ->label('Deskripsi')
                                            ->required()
                                            ->columnSpanFull(),
                                    ]),
                                Forms\Components\Section::make('Poster Event')
                                    ->schema([
                                        SpatieMediaLibraryFileUpload::make('event_posters')
                                            ->collection('event_posters')
                                            ->label('Upload Poster')
                                            ->image()
                                            ->responsiveImages()
                                            ->columnSpanFull(),
                                    ]),
                                Forms\Components\Section::make('Waktu & Lokasi')
                                    ->schema([
                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\DateTimePicker::make('start_date')
                                                    ->label('Tanggal Mulai')
                                                    ->required(),
                                                Forms\Components\DateTimePicker::make('end_date')
                                                    ->label('Tanggal Selesai')
                                                    ->required(),
                                            ]),
                                        Forms\Components\Toggle::make('is_online')
                                            ->label('Event Online?')
                                            ->live()
                                            ->required(),
                                        Forms\Components\TextInput::make('location')
                                            ->label('Lokasi')
                                            ->maxLength(255)
                                            ->hidden(fn (Get $get) => $get('is_online')),
                                    ]),
                            ]),
                        Forms\Components\Tabs\Tab::make('Pengaturan Tiket & Status')
                            ->icon('heroicon-o-ticket')
                            ->schema([
                                Forms\Components\Section::make('Tiket & Kursi')
                                    ->schema([
                                        Forms\Components\Toggle::make('has_numbered_seats')
                                            ->label('Gunakan Kursi Bernomor?')
                                            ->helperText('Aktifkan jika setiap tiket akan memiliki nomor kursi yang spesifik.')
                                            ->required(fn (Get $get) => !$get('is_online'))
                                            ->hidden(fn (Get $get) => $get('is_online')),
                                    ]),
                                Forms\Components\Section::make('Status Event')
                                    ->schema([
                                        Forms\Components\Select::make('status')
                                            ->label('Status')
                                            ->options([
                                                'draft' => 'Draft',
                                                'published' => 'Dipublikasikan',
                                                'completed' => 'Selesai',
                                                'cancelled' => 'Dibatalkan',
                                            ])
                                            ->native(false)
                                            ->required(),
                                    ]),
                            ]),
                    ])->columnSpanFull(),
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
                    ->label('Nama Event')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Pembuat')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Waktu Mulai')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'primary' => 'draft',
                        'success' => 'published',
                        'warning' => 'completed',
                        'danger' => 'cancelled',
                    ]),
                Tables\Columns\IconColumn::make('is_online')
                    ->label('Online')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Dipublikasikan',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ]),
                Tables\Filters\TernaryFilter::make('is_online')
                    ->label('Jenis Event')
                    ->trueLabel('Online')
                    ->falseLabel('Offline')
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}