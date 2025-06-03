<?php

namespace App\Filament\Resources\RewardredemptionResource\Pages;

use App\Filament\Resources\RewardredemptionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRewardredemption extends EditRecord
{
    protected static string $resource = RewardredemptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
