<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TicketResource\Pages;
use App\Models\Ticket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationLabel = 'Tiket';
    protected static ?string $modelLabel = 'Tiket';
    protected static ?string $pluralModelLabel = 'Tiket';
    protected static ?string $navigationGroup = 'Manajemen Event';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Detail Tiket')
                            ->schema([
                                Forms\Components\Select::make('event_id')
                                    ->relationship('event', 'title')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->label('Event'),

                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->label('Nama Tiket')
                                    ->placeholder('Contoh: VIP, Reguler, Early Bird'),

                                Forms\Components\RichEditor::make('description')
                                    ->columnSpanFull()
                                    ->label('Deskripsi'),
                            ])
                            ->columns(2),

                        Forms\Components\Section::make('Harga dan Inventaris')
                            ->schema([
                                Forms\Components\TextInput::make('price')
                                    ->numeric()
                                    ->prefix('IDR')
                                    ->required()
                                    ->label('Harga'),

                                Forms\Components\TextInput::make('quantity')
                                    ->numeric()
                                    ->required()
                                    ->label('Jumlah Tersedia'),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Ketersediaan Penjualan')
                            ->schema([
                                Forms\Components\DateTimePicker::make('available_from')
                                    ->required()
                                    ->label('Penjualan Dimulai'),

                                Forms\Components\DateTimePicker::make('available_to')
                                    ->required()
                                    ->label('Penjualan Berakhir'),
                            ]),
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

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Tiket')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->money('idr')
                    ->sortable(),

                Tables\Columns\TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->sortable(),

                Tables\Columns\TextColumn::make('available_from')
                    ->label('Mulai Dijual')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('available_to')
                    ->label('Selesai Dijual')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('event')
                    ->relationship('event', 'title')
                    ->searchable()
                    ->preload(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }
}
