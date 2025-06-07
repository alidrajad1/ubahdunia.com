<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RewardredemptionResource\Pages;
use App\Filament\Resources\RewardredemptionResource\RelationManagers;
use App\Models\Rewardredemption;
use App\Models\User;
use App\Models\Reward;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RewardredemptionResource extends Resource
{
    protected static ?string $model = Rewardredemption::class;

    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';

    protected static ?string $navigationGroup = 'User Management';

    protected static ?string $modelLabel = 'Reward Redemption';

    protected static ?string $pluralModelLabel = 'Reward Redemptions';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('reward_id')
                    ->label('Reward')
                    ->relationship('reward', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\DateTimePicker::make('redeemed_at')
                    ->label('Redeemed At')
                    ->default(now())
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

                Tables\Columns\TextColumn::make('user.name')
                    ->label('User Name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('reward.name')
                    ->label('Reward Name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('redeemed_at')
                    ->label('Redeemed At')
                    ->dateTime()
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
                    ->label('Filter by User')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('reward_id')
                    ->label('Filter by Reward')
                    ->relationship('reward', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\Filter::make('redeemed_at')
                    ->form([
                        Forms\Components\DatePicker::make('redeemed_from')
                            ->label('Redeemed From'),
                        Forms\Components\DatePicker::make('redeemed_until')
                            ->label('Redeemed Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['redeemed_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('redeemed_at', '>=', $date),
                            )
                            ->when(
                                $data['redeemed_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('redeemed_at', '<=', $date),
                            );
                    })
                    ->label('Redemption Date'),
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
            'index' => Pages\ListRewardredemptions::route('/'),
            'create' => Pages\CreateRewardredemption::route('/create'),
            'edit' => Pages\EditRewardredemption::route('/{record}/edit'),
        ];
    }
}
