<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Illuminate\Support\Str;

use Filament\Forms\Components\DateTimePicker;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('user_id')
                    ->default(auth()->id()),
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->nullable(),
                Select::make('tags')
                    ->relationship('tags', 'name')
                    ->multiple()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, $state, callable $set) => $set('slug', Str::slug($state))),
                        TextInput::make('slug')
                            ->required()
                            ->unique('tags', 'slug')
                    ]),
                TextInput::make('title')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, callable $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true),
                FileUpload::make('image')
                    ->image()
                    ->multiple()
                    ->maxFiles(5)
                    ->maxSize(2048)
                    ->directory('news-images')
                    ->columnSpanFull(),
                RichEditor::make('content')
                    ->columnSpanFull(),
                DateTimePicker::make('published_at')
                    ->label('Publish Date (Leave empty to publish immediately if status is Published)'),
                Select::make('status')
                    ->options(function () {
                        if (auth()->user()->is_admin) {
                            return [
                                'draft' => 'Draft',
                                'review' => 'In Review',
                                'published' => 'Published',
                                'rejected' => 'Rejected',
                            ];
                        }
                        return [
                            'draft' => 'Draft',
                            'review' => 'Submit for Review',
                        ];
                    })
                    ->required()
                    ->default('draft'),
            ]);
    }
}
