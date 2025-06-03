<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DonationResource\Pages;
use App\Filament\Resources\DonationResource\RelationManagers;
use App\Models\Donation;
use App\Models\User; // Import model User
use App\Models\Campaign; // Import model Campaign
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DonationResource extends Resource
{
    protected static ?string $model = Donation::class;

    protected static ?string $navigationIcon = 'heroicon-o-heart'; // Mengganti ikon menjadi heart

    protected static ?string $navigationGroup = 'Financial Management'; // Menambahkan grup navigasi

    protected static ?string $modelLabel = 'Donation'; // Label untuk model

    protected static ?string $pluralModelLabel = 'Donations'; // Label plural untuk model

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Donor User')
                    ->relationship('user', 'name') // Mengambil nama dari tabel users
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('campaign_id')
                    ->label('Campaign')
                    ->relationship('campaign', 'title') // Mengambil judul dari tabel campaigns
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('type')
                    ->label('Donation Type')
                    ->options([
                        'money' => 'Money',
                        'goods' => 'Goods',
                        'labor' => 'Labor',
                    ])
                    ->required()
                    ->reactive(), // Membuat field ini reaktif untuk kondisi amount

                Forms\Components\TextInput::make('amount')
                    ->label('Amount')
                    ->numeric()
                    ->prefix('Rp') // Menambahkan prefix mata uang
                    ->required(fn (Forms\Get $get): bool => $get('type') === 'money') // Wajib jika tipe adalah 'money'
                    ->nullable(fn (Forms\Get $get): bool => $get('type') !== 'money') // Nullable jika tipe bukan 'money'
                    ->rule('numeric')
                    ->step(0.01)
                    ->hidden(fn (Forms\Get $get): bool => $get('type') !== 'money'), // Sembunyikan jika tipe bukan 'money'

                Forms\Components\RichEditor::make('description')
                    ->label('Description')
                    ->nullable() // Deskripsi bisa null
                    ->columnSpanFull(), // Mengambil lebar penuh di form

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'processed' => 'Processed',
                        'sent' => 'Sent',
                        'completed' => 'Completed',
                    ])
                    ->required()
                    ->default('processed'), // Default status saat membuat donasi baru
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
                    ->label('Donor')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('campaign.title') // Menampilkan judul kampanye dari relasi
                    ->label('Campaign')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->sortable(),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount')
                    ->money('IDR') // Menampilkan sebagai mata uang Rupiah
                    ->default('-') // Tampilkan '-' jika amount null (untuk goods/labor)
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->limit(50) // Batasi panjang teks
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\BadgeColumn::make('status') // Menggunakan BadgeColumn untuk status
                    ->label('Status')
                    ->colors([
                        'warning' => 'processed',
                        'info' => 'sent',
                        'success' => 'completed',
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
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Filter by Donor')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('campaign_id')
                    ->label('Filter by Campaign')
                    ->relationship('campaign', 'title')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'money' => 'Money',
                        'goods' => 'Goods',
                        'labor' => 'Labor',
                    ])
                    ->label('Filter by Type'),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'processed' => 'Processed',
                        'sent' => 'Sent',
                        'completed' => 'Completed',
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
            // Jika Anda memiliki CommentsRelationManager untuk donasi, Anda bisa menambahkannya di sini
            // RelationManagers\CommentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDonations::route('/'),
            'create' => Pages\CreateDonation::route('/create'),
            'edit' => Pages\EditDonation::route('/{record}/edit'),
        ];
    }
}
