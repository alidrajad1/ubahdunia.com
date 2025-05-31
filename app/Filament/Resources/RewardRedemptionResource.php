<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RewardRedemptionResource\Pages;
use App\Filament\Resources\RewardRedemptionResource\RelationManagers;
use App\Models\RewardRedemption;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RewardRedemptionResource extends Resource
{
    protected static ?string $model = RewardRedemption::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket'; // Anda bisa mengubah ikon ini

    protected static ?string $pluralModelLabel = 'Reward Redemptions'; // Untuk label di navigasi Filament

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('reward_id')
                    ->relationship('reward', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                // 'redeemed_at' tidak perlu di form karena diisi otomatis oleh database (created_at/updated_at)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reward.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at') // redeemed_at akan diisi oleh created_at
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('reward_id')
                    ->relationship('reward', 'name')
                    ->label('Reward'),
                Tables\Filters\SelectFilter::make('user_id')
                    ->relationship('user', 'name')
                    ->label('User'),
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
            'index' => Pages\ListRewardRedemptions::route('/'),
            'create' => Pages\CreateRewardRedemption::route('/create'),
            'edit' => Pages\EditRewardRedemption::route('/{record}/edit'),
        ];
    }
}
