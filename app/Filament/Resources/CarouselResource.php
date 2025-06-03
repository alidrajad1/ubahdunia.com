<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarouselResource\Pages;
use App\Filament\Resources\CarouselResource\RelationManagers;
use App\Models\Carousel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString; // Import untuk HtmlString
use Illuminate\Support\Str; // Import untuk Str::limit

class CarouselResource extends Resource
{
    protected static ?string $model = Carousel::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo'; // Mengganti ikon menjadi photo

    protected static ?string $navigationGroup = 'Content Management'; // Menambahkan grup navigasi

    protected static ?string $modelLabel = 'Carousel Item'; // Label untuk model

    protected static ?string $pluralModelLabel = 'Carousel Items'; // Label plural untuk model

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('image_url')
                    ->label('Image')
                    ->image() // Memastikan hanya file gambar yang diunggah
                    ->directory('carousel-images') // Direktori penyimpanan gambar
                    ->visibility('public') // Mengatur visibilitas file
                    ->required(),

                Forms\Components\TextInput::make('caption')
                    ->label('Caption')
                    ->maxLength(255)
                    ->nullable(), // Caption bisa null sesuai skema (jika tidak ada default)

                Forms\Components\TextInput::make('link_url')
                    ->label('Link URL')
                    ->url() // Memastikan input adalah URL yang valid
                    ->maxLength(255)
                    ->nullable(), // Link URL bisa null sesuai skema (jika tidak ada default)

                Forms\Components\TextInput::make('display_order')
                    ->label('Display Order')
                    ->numeric()
                    ->default(0) // Default ke 0
                    ->minValue(0)
                    ->required(),

                Forms\Components\Toggle::make('is_active')
                    ->label('Is Active')
                    ->default(true) // Default ke true
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

                Tables\Columns\ImageColumn::make('image_url')
                    ->label('Image')
                    ->square(), // Menampilkan gambar dalam bentuk persegi

                Tables\Columns\TextColumn::make('caption')
                    ->label('Caption')
                    ->limit(50) // Batasi panjang teks
                    ->searchable(),

                Tables\Columns\TextColumn::make('link_url')
                    ->label('Link URL')
                    // Mengganti ->url() dan ->openUrlInNewTab() dengan formatStateUsing
                    ->formatStateUsing(function (?string $state): HtmlString {
                        if (empty($state)) {
                            return new HtmlString('N/A'); // Tampilkan 'N/A' jika URL kosong
                        }
                        // Validasi dasar URL dan buat tautan HTML
                        if (filter_var($state, FILTER_VALIDATE_URL)) {
                            return new HtmlString('<a href="' . htmlspecialchars($state) . '" target="_blank" rel="noopener noreferrer" class="text-primary-600 hover:underline">' . htmlspecialchars(Str::limit($state, 50)) . '</a>');
                        }
                        // Jika bukan URL valid, tampilkan teks biasa (dibatasi)
                        return new HtmlString(htmlspecialchars(Str::limit($state, 50)));
                    })
                    ->html() // Penting: memberitahu Filament untuk merender string sebagai HTML
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('display_order')
                    ->label('Order')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean() // Menampilkan ikon centang/silang
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
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->nullable()
                    ->trueLabel('Active')
                    ->falseLabel('Inactive')
                    ->indicator('Active Status'),

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
            'index' => Pages\ListCarousels::route('/'),
            'create' => Pages\CreateCarousel::route('/create'),
            'edit' => Pages\EditCarousel::route('/{record}/edit'),
        ];
    }
}
