<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Post;

class TopPostsWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    
    protected static ?int $sort = 2;
    
    protected static ?string $heading = 'Top 10 Most Viewed Posts';

    public function table(Table $table): Table
    {
        $user = auth()->user();
        
        $query = Post::query()
            ->orderBy('views_count', 'desc')
            ->limit(10);
            
        // Apply RBAC: non-admins only see stats for their own posts
        if (!$user->is_admin) {
            $query->where('user_id', $user->id);
        }

        return $table
            ->query($query)
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->badge(),
                Tables\Columns\TextColumn::make('views_count')
                    ->label('Views')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'review' => 'warning',
                        'draft' => 'gray',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->paginated(false);
    }
}
