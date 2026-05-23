<?php
namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\Blog;
use App\Models\Order;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Products', Product::count())
                ->description('Active: ' . Product::where('is_active', true)->count())
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('warning')
                ->chart([3, 5, 4, 8, 6, 9, 7]),

            Stat::make('Total Orders', Order::count())
                ->description('Pending: ' . Order::where('status', 'pending')->count())
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('success')
                ->chart([2, 4, 3, 6, 5, 8, 7]),

            Stat::make('Blog Posts', Blog::count())
                ->description('Published: ' . Blog::where('is_published', true)->count())
                ->descriptionIcon('heroicon-m-document-text')
                ->color('info')
                ->chart([1, 3, 2, 5, 4, 6, 5]),

            Stat::make('Registered Users', User::count())
                ->description('Total customers')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary')
                ->chart([4, 6, 5, 8, 7, 9, 8]),
        ];
    }
}