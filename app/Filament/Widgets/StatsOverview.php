<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use App\Models\Category;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    // To make it appear at the top of the dashboard, before other widgets
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $user = auth()->user();
        
        $postQuery = Post::query();
        
        // Apply RBAC: non-admins only see stats for their own posts
        if (!$user->is_admin) {
            $postQuery->where('user_id', $user->id);
        }

        $totalPosts = (clone $postQuery)->count();
        $totalViews = (clone $postQuery)->sum('views_count') ?? 0;
        $pendingReviews = (clone $postQuery)->where('status', 'review')->count();
        
        $stats = [
            Stat::make('Total Posts', number_format($totalPosts))
                ->description($user->is_admin ? 'Total articles across platform' : 'Your total articles')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('success'),
                
            Stat::make('Total Views', number_format($totalViews))
                ->description($user->is_admin ? 'Global page views' : 'Views on your articles')
                ->descriptionIcon('heroicon-m-eye')
                ->color('warning'),
        ];
        
        if ($user->is_admin) {
            $stats[] = Stat::make('Pending Review', number_format($pendingReviews))
                ->description('Posts waiting for approval')
                ->descriptionIcon('heroicon-m-clock')
                ->color('danger');
                
            $stats[] = Stat::make('Total Categories', number_format(Category::count()))
                ->description('Active categories')
                ->descriptionIcon('heroicon-m-tag')
                ->color('primary');
        } else {
            $stats[] = Stat::make('In Review', number_format($pendingReviews))
                ->description('Your posts waiting for approval')
                ->descriptionIcon('heroicon-m-clock')
                ->color('info');
        }

        return $stats;
    }
}
