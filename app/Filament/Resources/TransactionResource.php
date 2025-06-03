<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use App\Models\User; // Import model User
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar'; // Mengganti ikon menjadi currency-dollar

    protected static ?string $navigationGroup = 'Financial Management'; // Menambahkan grup navigasi

    protected static ?string $modelLabel = 'Transaction'; // Label untuk model

    protected static ?string $pluralModelLabel = 'Transactions'; // Label plural untuk model

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name') // Mengambil nama dari tabel users
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('type')
                    ->label('Transaction Type')
                    ->options([
                        'topup' => 'Top Up',
                        'donation' => 'Donation', // Diperbarui: 'donasi' menjadi 'donation'
                    ])
                    ->required(),

                Forms\Components\TextInput::make('amount')
                    ->label('Amount')
                    ->numeric()
                    ->prefix('Rp') // Menambahkan prefix mata uang
                    ->required()
                    ->rule('numeric') // Memastikan input adalah numerik
                    ->step(0.01), // Memungkinkan nilai desimal

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'success' => 'Success', // Diperbarui: 'berhasil' menjadi 'success'
                        'failed' => 'Failed',   // Diperbarui: 'gagal' menjadi 'failed'
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.name') // Menampilkan nama user dari relasi
                    ->label('User Name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount')
                    ->money('IDR') // Menampilkan sebagai mata uang Rupiah
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status') // Menggunakan BadgeColumn untuk status
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'success', // Diperbarui: 'berhasil' menjadi 'success'
                        'danger' => 'failed',     // Diperbarui: 'gagal' menjadi 'failed'
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'topup' => 'Top Up',
                        'donation' => 'Donation', // Diperbarui: 'donasi' menjadi 'donation'
                    ])
                    ->label('Filter by Type'),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'success' => 'Success', // Diperbarui: 'berhasil' menjadi 'success'
                        'failed' => 'Failed',   // Diperbarui: 'gagal' menjadi 'failed'
                    ])
                    ->label('Filter by Status'),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
