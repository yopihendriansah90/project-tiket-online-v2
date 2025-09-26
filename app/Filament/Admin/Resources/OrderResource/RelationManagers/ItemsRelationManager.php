<?php

namespace App\Filament\Admin\Resources\OrderResource\RelationManagers;

use App\Models\OrderItem;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $title = 'Item Pesanan';
    protected static ?string $modelLabel = 'Item';
    protected static ?string $pluralModelLabel = 'Item';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Form is read-only, so we define it in the table view.
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ticket_name')
                    ->label('Nama Tiket'),

                Tables\Columns\TextColumn::make('price')
                    ->label('Harga Satuan')
                    ->money('idr'),

                Tables\Columns\TextColumn::make('quantity')
                    ->label('Jumlah'),

                Tables\Columns\TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->money('idr'),

                Tables\Columns\TextColumn::make('attendees_list')
                    ->label('Peserta & Kursi')
                    ->formatStateUsing(function (OrderItem $record) {
                        if ($record->attendees->isEmpty()) {
                            return '-';
                        }

                        return $record->attendees->map(function ($attendee) {
                            $seatInfo = $attendee->seat ? " ({$attendee->seat->area} / Baris: {$attendee->seat->row} / No: {$attendee->seat->number})" : '';
                            return $attendee->name . $seatInfo;
                        })->implode('<br>');
                    })
                    ->html(),
            ])
            ->actions([])
            ->bulkActions([]);
    }
}
