<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AttendeeResource\Pages;
use App\Models\Attendee;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AttendeeResource extends Resource
{
    protected static ?string $model = Attendee::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Peserta';
    protected static ?string $modelLabel = 'Peserta';
    protected static ?string $pluralModelLabel = 'Peserta';
    protected static ?string $navigationGroup = 'Manajemen Acara';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Peserta')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->label('Alamat Email')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->label('Nomor HP')
                            ->required()
                            ->maxLength(20),
                        Forms\Components\TextInput::make('checkin_gate')
                            ->label('Gate/Pos')
                            ->maxLength(50),
                        Forms\Components\DateTimePicker::make('checked_in_at')
                            ->label('Waktu Check-in'),
                        Forms\Components\Placeholder::make('unique_token_display')
                            ->label('Token Unik')
                            ->content(fn ($record) => $record?->unique_token ?? '-'),
                    ])->columns(2),

                Forms\Components\Section::make('Detail Tiket & Acara')
                    ->schema([
                        Forms\Components\Select::make('order_item_id')
                            ->relationship('orderItem', 'id') // Ganti 'id' dengan kolom yang lebih deskriptif jika ada
                            ->label('Item Pesanan')
                            ->disabled(),
                        Forms\Components\Placeholder::make('event_name')
                            ->label('Acara')
                            ->content(fn ($record) => $record?->orderItem?->ticket?->event?->title ?? '-'),
                        Forms\Components\Placeholder::make('ticket_name')
                            ->label('Jenis Tiket')
                            ->content(fn ($record) => $record?->orderItem?->ticket?->name ?? '-'),
                        Forms\Components\Placeholder::make('order_invoice')
                            ->label('No. Invoice')
                            ->content(fn ($record) => $record?->orderItem?->order?->invoice_number ?? '-'),
                    ])->columns(2),

                Forms\Components\Section::make('Informasi Kursi')
                    ->schema([
                        Forms\Components\Select::make('seat_id')
                            ->relationship('seat', 'id') // Ganti 'id' dengan kolom yang lebih deskriptif
                            ->label('Kursi'),
                        Forms\Components\Placeholder::make('seat_info')
                            ->label('Detail Kursi')
                            ->content(function ($record) {
                                if ($record?->seat) {
                                    $seat = $record->seat;
                                    return "Area: {$seat->area}, Baris: {$seat->row}, No: {$seat->number}";
                                }
                                return '-';
                            }),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Peserta')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Nomor HP')
                    ->searchable(),
                Tables\Columns\TextColumn::make('unique_token')
                    ->label('Token')
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('orderItem.ticket.event.title')
                    ->label('Acara')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('orderItem.ticket.name')
                    ->label('Jenis Tiket')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('seat')
                    ->label('Kursi')
                    ->formatStateUsing(fn ($state) => $state ? "{$state->area} - {$state->row}{$state->number}" : '-'),
                Tables\Columns\TextColumn::make('checkin_gate')
                    ->label('Gate/Pos')
                    ->searchable(),
                Tables\Columns\TextColumn::make('checked_in_status')
                    ->label('Status Check-in')
                    ->badge()
                    ->getStateUsing(fn ($record) => $record->checked_in_at ? 'Checked-in' : 'Belum')
                    ->color(fn ($record) => $record->checked_in_at ? 'success' : 'gray')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('event')
                    ->label('Filter berdasarkan Acara')
                    ->relationship('orderItem.ticket.event', 'title')
                    ->searchable()
                    ->preload(),
                Tables\Filters\TernaryFilter::make('checked_in_at')
                    ->label('Status Check-in')
                    ->nullable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('checkin')
                    ->label('Check-in')
                    ->icon('heroicon-m-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->form([
                        Forms\Components\TextInput::make('checkin_gate')
                            ->label('Gate/Pos')
                            ->maxLength(50),
                    ])
                    ->action(function (Attendee $record, array $data) {
                        $record->checked_in_at = now();
                        if (!empty($data['checkin_gate'])) {
                            $record->checkin_gate = $data['checkin_gate'];
                        }
                        $record->save();
                    })
                    ->visible(fn (Attendee $record) => is_null($record->checked_in_at)),
                Tables\Actions\Action::make('undo_checkin')
                    ->label('Batalkan Check-in')
                    ->icon('heroicon-m-x-mark')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (Attendee $record) {
                        $record->checked_in_at = null;
                        $record->save();
                    })
                    ->visible(fn (Attendee $record) => !is_null($record->checked_in_at)),
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
            'index' => Pages\ListAttendees::route('/'),
            'edit' => Pages\EditAttendee::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
