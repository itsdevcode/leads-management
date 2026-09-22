<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Leads', \App\Models\Lead::count())
                ->description('All time leads')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),
            Stat::make('Converted Leads', \App\Models\Lead::where('status', 'converted')->count())
                ->description('Successfully converted')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('primary'),
            Stat::make('Pending Follow-ups', \App\Models\Reminder::where('status', 'pending')->count())
                ->description('To do')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
        ];
    }
}
