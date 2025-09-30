<?php

namespace App\Filament\Admin\Resources\EventResource\RelationManagers;

use App\Models\Seat;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Unique;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class SeatsRelationManager extends RelationManager
{
    protected static string $relationship = 'seats';

    protected static ?string $recordTitleAttribute = 'area';

    public static function canViewForRecord(Model $ownerRecord, string $pageName): bool
    {
        return !$ownerRecord->is_online;
    }

    protected static ?string $title = 'Kursi';
    protected static ?string $modelLabel = 'Kursi';
    protected static ?string $pluralModelLabel = 'Kursi';


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('ticket_id')
                    ->label('Jenis Tiket')
                    ->relationship(
                        name: 'ticket',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn (Builder $query) => $query->where('event_id', $this->ownerRecord->id)
                    )
                    ->required(),
                Forms\Components\TextInput::make('area')
                    ->label('Area / Zona Kursi')
                    ->helperText('Contoh: VIP A, Festival B')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('row')
                    ->label('Baris Kursi')
                    ->helperText('Contoh: A, B, atau 1, 2')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('number')
                    ->label('Nomor Kursi')
                    ->numeric()
                    ->required()
                    ->unique(
                        modifyRuleUsing: function (Unique $rule, Get $get) {
                            return $rule
                                ->where('row', $get('row'))
                                ->where('event_id', $this->ownerRecord->id);
                        },
                        ignoreRecord: true
                    )
                    ->validationMessages([
                        'unique' => 'Kombinasi Baris dan Nomor Kursi ini sudah ada untuk event ini.',
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ticket.name')
                    ->label('Jenis Tiket')
                    ->searchable(),
                Tables\Columns\TextColumn::make('area')
                    ->label('Area')
                    ->searchable(),
                Tables\Columns\TextColumn::make('row')
                    ->label('Baris')
                    ->searchable(),
                Tables\Columns\TextColumn::make('number')
                    ->label('Nomor')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_available')
                    ->label('Tersedia')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
