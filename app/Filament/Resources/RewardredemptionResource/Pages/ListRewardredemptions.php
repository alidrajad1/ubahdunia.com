<?php

namespace App\Filament\Resources\RewardredemptionResource\Pages;

use App\Filament\Resources\RewardredemptionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRewardredemptions extends ListRecords
{
    protected static string $resource = RewardredemptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
