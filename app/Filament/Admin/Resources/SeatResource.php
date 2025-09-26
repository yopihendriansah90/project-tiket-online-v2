<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SeatResource\Pages;
use App\Models\Event;
use App\Models\Seat;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rules\Unique;

class SeatResource extends Resource
{
    protected static ?string $model = Seat::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationLabel = 'Kursi Event Fisik';
    protected static ?string $modelLabel = 'Kursi';
    protected static ?string $pluralModelLabel = 'Kursi';
    protected static ?string $navigationGroup = 'Manajemen Event';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Informasi Event & Tiket')
                            ->schema([
                                Forms\Components\Select::make('event_id')
                                    ->relationship(
                                        name: 'event',
                                        titleAttribute: 'title',
                                        modifyQueryUsing: fn (Builder $query) => $query->where('is_online', false)->where('has_numbered_seats', true)
                                    )
                                    ->label('Event')
                                    ->helperText('Hanya event fisik dengan penomoran kursi yang akan tampil.')
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->required(),

                                Forms\Components\Select::make('ticket_id')
                                    ->label('Jenis Tiket')
                                    ->helperText('Pilih jenis tiket yang berelasi dengan kursi ini.')
                                    ->options(function (Get $get) {
                                        $event = Event::find($get('event_id'));
                                        if (!$event) {
                                            return [];
                                        }
                                        return $event->tickets()->pluck('name', 'id');
                                    })
                                    ->searchable()
                                    ->required(),
                            ])
                            ->columns(2),

                        Forms\Components\Section::make('Detail Lokasi Kursi')
                            ->schema([
                                Forms\Components\TextInput::make('area')
                                    ->label('Area / Zona')
                                    ->placeholder('Contoh: VIP, Festival A')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('row')
                                    ->label('Baris')
                                    ->placeholder('Contoh: A, 15')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('number')
                                    ->label('Nomor Kursi')
                                    ->placeholder('Contoh: 12, C3')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(
                                        modifyRuleUsing: function (Unique $rule, Get $get) {
                                            return $rule
                                                ->where('event_id', $get('event_id'))
                                                ->where('row', $get('row'));
                                        },
                                        ignoreRecord: true
                                    )
                                    ->validationMessages([
                                        'unique' => 'Kombinasi Event, Baris, dan Nomor Kursi ini sudah terdaftar.',
                                    ]),
                            ])
                            ->columns(3),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Status & Ketersediaan')
                            ->schema([
                                Forms\Components\Toggle::make('is_available')
                                    ->label('Tersedia untuk dipesan')
                                    ->default(true)
                                    ->required(),

                                Forms\Components\DateTimePicker::make('reserved_until')
                                    ->label('Reservasi sementara')
                                    ->helperText('Jika diisi, kursi akan ditahan hingga waktu yang ditentukan.'),
                            ]),
                        Forms\Components\Section::make('Informasi Pemegang Tiket')
                            ->schema([
                                Forms\Components\Placeholder::make('attendee_name')
                                    ->label('Nama Peserta')
                                    ->content(fn (?Seat $record) => $record?->attendee?->name ?? '-'),
                                Forms\Components\Placeholder::make('attendee_email')
                                    ->label('Email Peserta')
                                    ->content(fn (?Seat $record) => $record?->attendee?->email ?? '-'),
                                Forms\Components\Placeholder::make('order_id')
                                    ->label('ID Pesanan')
                                    ->content(fn (?Seat $record) => $record?->orderItem?->order_id ?? '-'),
                            ])
                            ->hiddenOn('create'),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('event.title')
                    ->label('Event')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('ticket.name')
                    ->label('Jenis Tiket')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('location')
                    ->label('Lokasi Kursi')
                    ->getStateUsing(fn (Seat $record): string => "{$record->area} / Baris: {$record->row} / No: {$record->number}")
                    ->searchable(['area', 'row', 'number']),

                Tables\Columns\BadgeColumn::make('is_available')
                    ->label('Status')
                    ->formatStateUsing(fn (bool $state) => $state ? 'Tersedia' : 'Dipesan')
                    ->color(fn (bool $state) => $state ? 'success' : 'danger'),

                Tables\Columns\TextColumn::make('attendee.name')
                    ->label('Pemegang Tiket')
                    ->searchable()
                    ->sortable()
                    ->default('-'),

                Tables\Columns\TextColumn::make('reserved_until')
                    ->label('Reservasi Hingga')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('event')
                    ->relationship('event', 'title')
                    ->searchable()
                    ->preload(),
                Tables\Filters\TernaryFilter::make('is_available')
                    ->label('Status Ketersediaan')
                    ->boolean()
                    ->trueLabel('Tersedia')
                    ->falseLabel('Dipesan'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSeats::route('/'),
            'create' => Pages\CreateSeat::route('/create'),
            'edit' => Pages\EditSeat::route('/{record}/edit'),
        ];
    }
}
