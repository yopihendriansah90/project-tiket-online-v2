<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TicketResource\Pages;
use App\Filament\Admin\Resources\TicketResource\RelationManagers;
use App\Models\Ticket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Ticket Details')
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
                                    ->label('Ticket Name'),

                                Forms\Components\RichEditor::make('description')
                                    ->columnSpanFull()
                                    ->label('Description'),
                            ])
                            ->columns(2),

                        Forms\Components\Section::make('Pricing and Inventory')
                            ->schema([
                                Forms\Components\TextInput::make('price')
                                    ->numeric()
                                    ->prefix('IDR')
                                    ->required()
                                    ->label('Price'),

                                Forms\Components\TextInput::make('quantity')
                                    ->numeric()
                                    ->required()
                                    ->label('Quantity Available'),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Availability')
                            ->schema([
                                Forms\Components\DateTimePicker::make('available_from')
                                    ->required()
                                    ->label('Sales Start'),

                                Forms\Components\DateTimePicker::make('available_to')
                                    ->required()
                                    ->label('Sales End'),
                            ]),

                        Forms\Components\Section::make('Seating Options')
                            ->schema([
                                Forms\Components\Toggle::make('is_seating_enabled')
                                    ->label('Enable Numbered Seating')
                                    ->reactive()
                                    ->helperText('Enable this if tickets correspond to specific, numbered seats.'),

                                Forms\Components\TextInput::make('seat_area')
                                    ->label('Seat Area/Section')
                                    ->placeholder('e.g., VIP, Section A, Balcony')
                                    ->helperText('Define the area or section this ticket applies to.')
                                    ->visible(fn ($get) => $get('is_seating_enabled')),
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
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->money('idr')
                    ->sortable(),

                Tables\Columns\TextColumn::make('quantity')
                    ->sortable(),

                Tables\Columns\TextColumn::make('available_from')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('available_to')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_seating_enabled')
                    ->label('Seating')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('event')
                    ->relationship('event', 'title'),

                Tables\Filters\TernaryFilter::make('is_seating_enabled')
                    ->label('Numbered Seating'),
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
