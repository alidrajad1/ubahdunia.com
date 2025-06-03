<?php

namespace App\Filament\Widgets;

use App\Models\Campaign; // Import model Campaign
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number; // Import Number helper for currency formatting

class Overview extends BaseWidget
{
    protected function getStats(): array
    {
        // Menghitung total kampanye
        $totalCampaigns = Campaign::count();

        // Menghitung kampanye aktif
        $activeCampaigns = Campaign::where('status', 'active')->count();

        // Menghitung kampanye selesai
        $finishedCampaigns = Campaign::where('status', 'finished')->count();

        // Menghitung total target amount
        $totalTargetAmount = Campaign::sum('target_amount');

        // Menghitung total collected amount
        $totalCollectedAmount = Campaign::sum('collected_amount');

        return [
            Stat::make('Total Campaigns', $totalCampaigns)
                ->description('All campaigns in the system')
                ->descriptionIcon('heroicon-m-megaphone')
                ->color('primary'), // Warna primer

            Stat::make('Active Campaigns', $activeCampaigns)
                ->description('Campaigns currently running')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'), // Warna hijau untuk aktif

            Stat::make('Finished Campaigns', $finishedCampaigns)
                ->description('Campaigns that have concluded')
                ->descriptionIcon('heroicon-m-archive-box')
                ->color('info'), // Warna biru untuk selesai

            Stat::make('Total Target Amount', Number::currency($totalTargetAmount, 'IDR')) // Format sebagai mata uang IDR
                ->description('Combined target for all campaigns')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('warning'), // Warna kuning untuk target

            Stat::make('Total Collected Amount', Number::currency($totalCollectedAmount, 'IDR')) // Format sebagai mata uang IDR
                ->description('Combined amount collected across all campaigns')
                ->descriptionIcon('heroicon-m-wallet')
                ->color('success'), // Warna hijau untuk jumlah terkumpul
        ];
    }
}
