<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CampaignResource\Pages;
use App\Filament\Resources\CampaignResource\RelationManagers;
use App\Models\Campaign;
use Filament\Forms;
use Filament\Forms\Components\FileUpload; // Pastikan ini sudah diimpor
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str; // Import Str facade for slug generation

class CampaignResource extends Resource
{
    protected static ?string $model = Campaign::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone'; // Mengganti ikon menjadi megaphone

    protected static ?string $navigationGroup = 'Content Management'; // Menambahkan grup navigasi

    protected static ?string $modelLabel = 'Campaign'; // Label untuk model

    protected static ?string $pluralModelLabel = 'Campaigns'; // Label plural untuk model

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Campaign Title')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true) // Memperbarui slug secara real-time saat judul diisi
                    ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),

                Forms\Components\TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true), // Pastikan slug unik, kecuali saat mengedit record yang sama

                Forms\Components\FileUpload::make('image_url') // Menambahkan field untuk gambar
                    ->label('Campaign Image')
                    ->image() // Memastikan hanya file gambar yang diunggah
                    ->directory('campaign-images') // Direktori penyimpanan gambar di storage/app/public/campaign-images
                    ->visibility('public') // Mengatur visibilitas file agar bisa diakses publik
                    ->nullable(), // Sesuai dengan migrasi, kolom ini bisa null

                Forms\Components\RichEditor::make('description')
                    ->label('Description')
                    ->required()
                    ->columnSpanFull(), // Mengambil lebar penuh di form

                Forms\Components\TextInput::make('target_amount')
                    ->label('Target Amount')
                    ->numeric()
                    ->prefix('Rp') // Menambahkan prefix mata uang
                    ->required()
                    ->minValue(0)
                    ->step(0.01),

                Forms\Components\TextInput::make('collected_amount')
                    ->label('Collected Amount')
                    ->numeric()
                    ->prefix('Rp') // Menambahkan prefix mata uang
                    ->default(0.00) // Default ke 0.00
                    ->minValue(0)
                    ->step(0.01)
                    ->readOnly(), // Biasanya dihitung otomatis, tidak diedit manual

                Forms\Components\DatePicker::make('start_date')
                    ->label('Start Date')
                    ->required(),

                Forms\Components\DatePicker::make('end_date')
                    ->label('End Date')
                    ->required()
                    ->afterOrEqual('start_date'), // Tanggal berakhir harus setelah atau sama dengan tanggal mulai

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Active',
                        'finished' => 'Finished',
                        'draft' => 'Draft',
                    ])
                    ->required()
                    ->default('draft'), // Default status saat membuat kampanye baru
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

                Tables\Columns\ImageColumn::make('image_url') // Menambahkan kolom gambar di tabel
                    ->label('Image')
                    ->square() // Menampilkan gambar dalam bentuk persegi
                    ->toggleable(), // Memungkinkan pengguna untuk menyembunyikan/menampilkan kolom ini

                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->sortable()
                    ->searchable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('target_amount')
                    ->label('Target')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('collected_amount')
                    ->label('Collected')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Start Date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('end_date')
                    ->label('End Date')
                    ->date()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'active',
                        'info' => 'finished',
                        'warning' => 'draft',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

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
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'finished' => 'Finished',
                        'draft' => 'Draft',
                    ])
                    ->label('Filter by Status'),

                Tables\Filters\Filter::make('date_range')
                    ->form([
                        Forms\Components\DatePicker::make('start_date_from')
                            ->label('Start Date From'),
                        Forms\Components\DatePicker::make('start_date_to')
                            ->label('Start Date To'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['start_date_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('start_date', '>=', $date),
                            )
                            ->when(
                                $data['start_date_to'],
                                fn (Builder $query, $date): Builder => $query->whereDate('start_date', '<=', $date),
                            );
                    })
                    ->label('Start Date Range'),
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
            // RelationManagers\DonationsRelationManager::class, // Untuk melihat donasi terkait kampanye
            // RelationManagers\CommentsRelationManager::class, // Untuk melihat komentar terkait kampanye
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCampaigns::route('/'),
            'create' => Pages\CreateCampaign::route('/create'),
            'edit' => Pages\EditCampaign::route('/{record}/edit'),
        ];
    }
}
