<?php

namespace App\Filament\Admin\Resources\OrderResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    protected static ?string $title = 'Pembayaran';
    protected static ?string $modelLabel = 'Pembayaran';
    protected static ?string $pluralModelLabel = 'Pembayaran';

    public function form(Form $form): Form
    {
        // Read-only; pengelolaan verifikasi dapat ditambahkan sebagai actions terpisah
        return $form->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('channel_label')
                    ->label('Metode')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Nominal')
                    ->money('idr')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'submitted',
                        'success' => 'verified',
                        'danger' => 'rejected',
                    ])
                    ->sortable(),

                // Preview bukti transfer via accessor URL
                Tables\Columns\ImageColumn::make('proof_url')
                    ->label('Bukti')
                    ->circular()
                    ->extraAttributes(['style' => 'width:60px;height:60px;object-fit:cover']),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu Pembayaran')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('verified_at')
                    ->label('Diverifikasi')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->headerActions([]) // pembayaran oleh customer, admin hanya verifikasi
            ->actions([
                // Preview bukti dengan modal dan kemampuan zoom
                Tables\Actions\Action::make('preview')
                    ->label('Preview Bukti')
                    ->icon('heroicon-o-eye')
                    ->visible(fn ($record) => (bool) $record->proof_url)
                    ->modalHeading('Preview Bukti Transfer')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->modalContent(fn ($record) => view('admin.payments.preview', ['url' => $record->proof_url, 'paymentId' => $record->id])),

                // Verifikasi pembayaran
                Tables\Actions\Action::make('verify')
                    ->label('Verifikasi')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'submitted')
                    ->form([
                        Textarea::make('notes')
                            ->label('Catatan verifikasi')
                            ->maxLength(1000),
                    ])
                    ->requiresConfirmation()
                    ->action(function ($record, array $data) {
                        // Update payment
                        $record->status = 'verified';
                        $record->verified_by = Auth::id();
                        $record->verified_at = now();
                        if (!empty($data['notes'])) {
                            $record->notes = $data['notes'];
                        }
                        $record->save();

                        // Update order
                        $order = $record->order;
                        $order->status = 'paid';
                        $order->paid_at = now();
                        if ($order->payment_method !== 'manual_transfer') {
                            $order->payment_method = 'manual_transfer';
                        }
                        $order->save();
                    }),

                // Tolak pembayaran
                Tables\Actions\Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) => $record->status === 'submitted')
                    ->form([
                        Textarea::make('notes')
                            ->label('Alasan penolakan')
                            ->required()
                            ->maxLength(1000),
                    ])
                    ->requiresConfirmation()
                    ->action(function ($record, array $data) {
                        $record->status = 'rejected';
                        $record->verified_by = Auth::id();
                        $record->verified_at = now();
                        $record->notes = $data['notes'] ?? null;
                        $record->save();
                    }),
            ])
            ->bulkActions([]);
    }
}