<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommentResource\Pages;
use App\Filament\Resources\CommentResource\RelationManagers;
use App\Models\Comment;
use App\Models\User;
use App\Models\Campaign;
use App\Models\Request;
use App\Models\Donation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class CommentResource extends Resource
{
    protected static ?string $model = Comment::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?string $modelLabel = 'Comment';

    protected static ?string $pluralModelLabel = 'Comments';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Author')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('parent_id')
                    ->label('Parent Comment')
                    ->relationship('parent', 'content')
                    ->getOptionLabelFromRecordUsing(fn (Comment $record) => Str::limit(strip_tags($record->content), 50))
                    ->searchable()
                    ->preload()
                    ->nullable(),

                Forms\Components\Select::make('commentable_type')
                    ->label('Commentable Type')
                    ->options([
                        'App\\Models\\Campaign' => 'Campaign',
                        'App\\Models\\Request' => 'Request',
                        'App\\Models\\Donation' => 'Donation',

                    ])
                    ->required()
                    ->live(),

                Forms\Components\Select::make('commentable_id')
                    ->label('Commentable Item')
                    ->required()
                    ->options(function (Forms\Get $get) {
                        $type = $get('commentable_type');
                        if (!$type) {
                            return [];
                        }

                        if ($type === 'App\\Models\\Campaign') {
                            return Campaign::pluck('title', 'id')->toArray();
                        } elseif ($type === 'App\\Models\\Request') {
                            return Request::pluck('title', 'id')->toArray();
                        } elseif ($type === 'App\\Models\\Donation') {

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
                    ->columnSpanFull(),
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

                Tables\Columns\TextColumn::make('parent.id')
                    ->label('Parent ID')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('commentable_type')
                    ->label('Type')
                    ->formatStateUsing(fn (string $state): string => str_replace('App\\Models\\', '', $state))
                    ->sortable(),

                Tables\Columns\TextColumn::make('commentable_id')
                    ->label('Item ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('content')
                    ->label('Content')
                    ->html()
                    ->limit(100)
                    ->tooltip(fn (Comment $record): ?string => new HtmlString($record->content))
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
