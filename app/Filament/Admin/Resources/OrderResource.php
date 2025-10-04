<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\OrderResource\Pages;
use App\Filament\Admin\Resources\OrderResource\RelationManagers;
use App\Filament\Admin\Resources\OrderResource\Widgets\OrderStats;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = 'Pesanan';
    protected static ?string $modelLabel = 'Pesanan';
    protected static ?string $pluralModelLabel = 'Pesanan';
    protected static ?string $navigationGroup = 'Manajemen Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Detail Pesanan')
                            ->schema([
                                Forms\Components\Placeholder::make('invoice_number')
                                    ->label('No. Invoice')
                                    ->content(fn ($record) => $record->invoice_number),

                                Forms\Components\Placeholder::make('status')
                                    ->label('Status')
                                    ->content(fn ($record) => $record->status)
                                    ->helperText(fn ($record) => match($record->status) {
                                        'pending' => 'Pesanan menunggu pembayaran.',
                                        'paid' => 'Pembayaran telah berhasil' . ($record->paid_at ? ' pada ' . $record->paid_at->format('d M Y, H:i') : '.'),
                                        'failed' => 'Pembayaran gagal.',
                                        'cancelled' => 'Pesanan telah dibatalkan.',
                                        default => 'Status tidak diketahui.'
                                    }),

                                Forms\Components\Placeholder::make('total_price')
                                    ->label('Total Harga')
                                    ->content(fn ($record) => 'Rp ' . number_format($record->total_price, 0, ',', '.')),

                                Forms\Components\Placeholder::make('created_at')
                                    ->label('Tanggal Pesanan')
                                    ->content(fn ($record) => $record->created_at->format('d M Y, H:i')),
                            ])->columns(2),

                        Forms\Components\Section::make('Detail Pelanggan')
                            ->schema([
                                Forms\Components\Placeholder::make('user.name')
                                    ->label('Nama')
                                    ->content(fn ($record) => $record->user->name),

                                Forms\Components\Placeholder::make('user.email')
                                    ->label('Email')
                                    ->content(fn ($record) => $record->user->email),
                            ])->columns(2),

                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Pembayaran')
                            ->schema([
                                Forms\Components\Placeholder::make('payment_method')
                                    ->label('Metode Pembayaran')
                                    ->content(fn ($record) => $record->payment_method ?? '-'),

                                Forms\Components\Placeholder::make('paid_at')
                                    ->label('Waktu Pembayaran')
                                    ->content(fn ($record) => $record->paid_at ? $record->paid_at->format('d M Y, H:i') : '-'),
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
                Tables\Columns\TextColumn::make('invoice_number')
                    ->label('Invoice')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'paid',
                        'danger' => 'failed',
                        'secondary' => 'cancelled',
                    ])
                    ->searchable(),

                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total')
                    ->money('idr')
                    ->sortable(),

                Tables\Columns\TextColumn::make('items_count')
                    ->label('Jumlah Item')
                    ->counts('items')
                    ->sortable(),

                Tables\Columns\TextColumn::make('paid_at')
                    ->label('Dibayar pada')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'failed' => 'Failed',
                        'cancelled' => 'Cancelled',
                    ]),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')->label('Dari tanggal'),
                        Forms\Components\DatePicker::make('created_until')->label('Sampai tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            RelationManagers\ItemsRelationManager::class,
            RelationManagers\PaymentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'view' => Pages\ViewOrder::route('/{record}'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            OrderStats::class,
        ];
    }
}
