<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommentResource\Pages;
use App\Filament\Resources\CommentResource\RelationManagers;
use App\Models\Comment;
use App\Models\User; // Import model User
use App\Models\Campaign; // Import model Campaign (jika commentable_type bisa campaign)
use App\Models\Request; // Import model Request (jika commentable_type bisa request)
use App\Models\Donation; // Import model Donation (jika commentable_type bisa donation)
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString; // Untuk menampilkan HTML di kolom
use Illuminate\Support\Str;

class CommentResource extends Resource
{
    protected static ?string $model = Comment::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right'; // Mengganti ikon menjadi chat-bubble-left-right

    protected static ?string $navigationGroup = 'Content Management'; // Menambahkan grup navigasi

    protected static ?string $modelLabel = 'Comment'; // Label untuk model

    protected static ?string $pluralModelLabel = 'Comments'; // Label plural untuk model

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Author')
                    ->relationship('user', 'name') // Mengambil nama dari tabel users
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('parent_id')
                    ->label('Parent Comment')
                    ->relationship('parent', 'content') // Mengambil konten komentar induk
                    ->getOptionLabelFromRecordUsing(fn (Comment $record) => Str::limit(strip_tags($record->content), 50)) // Batasi panjang dan hapus tag HTML
                    ->searchable()
                    ->preload()
                    ->nullable(), // parent_id bisa null

                Forms\Components\Select::make('commentable_type')
                    ->label('Commentable Type')
                    ->options([
                        'App\\Models\\Campaign' => 'Campaign',
                        'App\\Models\\Request' => 'Request',
                        'App\\Models\\Donation' => 'Donation',
                        // Tambahkan model lain yang bisa dikomentari di sini
                    ])
                    ->required()
                    ->live(), // Membuat field ini reaktif untuk kondisi commentable_id

                Forms\Components\Select::make('commentable_id')
                    ->label('Commentable Item')
                    ->required()
                    ->options(function (Forms\Get $get) {
                        $type = $get('commentable_type');
                        if (!$type) {
                            return [];
                        }
                        // Sesuaikan dengan model yang sesuai
                        if ($type === 'App\\Models\\Campaign') {
                            return Campaign::pluck('title', 'id')->toArray();
                        } elseif ($type === 'App\\Models\\Request') {
                            return Request::pluck('title', 'id')->toArray();
                        } elseif ($type === 'App\\Models\\Donation') {
                            // Untuk donasi, mungkin perlu menampilkan sesuatu yang unik, misal: user_id dan amount
                            return Donation::all()->mapWithKeys(function ($donation) {
                                return [$donation->id => 'Donation by ' . ($donation->user ? $donation->user->name : 'Unknown User') . ' (Rp' . number_format($donation->amount, 0, ',', '.') . ')'];
                            })->toArray();
                        }
                        return [];
                    })
                    ->searchable()
                    ->preload(),

                Forms\Components\RichEditor::make('content')
                    ->label('Comment Content')
                    ->required()
                    ->columnSpanFull(), // Mengambil lebar penuh di form
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

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Author')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('parent.id') // Menampilkan ID komentar induk
                    ->label('Parent ID')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('commentable_type')
                    ->label('Type')
                    ->formatStateUsing(fn (string $state): string => str_replace('App\\Models\\', '', $state)) // Menampilkan hanya nama model
                    ->sortable(),

                Tables\Columns\TextColumn::make('commentable_id')
                    ->label('Item ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('content')
                    ->label('Content')
                    ->html() // Menampilkan konten sebagai HTML
                    ->limit(100) // Batasi panjang teks
                    ->tooltip(fn (Comment $record): ?string => new HtmlString($record->content)) // Tampilkan tooltip dengan konten lengkap
                    ->searchable(),

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
                    ->label('Filter by Author')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('commentable_type')
                    ->label('Filter by Type')
                    ->options([
                        'App\\Models\\Campaign' => 'Campaign',
                        'App\\Models\\Request' => 'Request',
                        'App\\Models\\Donation' => 'Donation',
                    ]),

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
            // Anda bisa menambahkan relasi untuk melihat balasan komentar di sini
            // RelationManagers\RepliesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListComments::route('/'),
            'create' => Pages\CreateComment::route('/create'),
            'edit' => Pages\EditComment::route('/{record}/edit'),
        ];
    }
}
