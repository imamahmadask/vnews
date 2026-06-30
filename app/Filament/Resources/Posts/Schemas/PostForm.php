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
use Filament\Forms\Components\Repeater;

use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

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
                Repeater::make('images_with_captions')
                    ->label('Foto/Gambar Post')
                    ->schema([
                        FileUpload::make('path')
                            ->label('File Gambar')
                            ->image()
                            ->maxSize(2048)
                            ->disk('public')
                            ->directory('news-images')
                            ->saveUploadedFileUsing(function (TemporaryUploadedFile $file): string {
                                $manager = new ImageManager(new Driver());
                                $image = $manager->decodePath($file->getRealPath());
                                
                                 // Add watermark if exists
                                 $watermarkPath = public_path('images/watermark.png');
                                 if (file_exists($watermarkPath)) {
                                     $watermark = $manager->decodePath($watermarkPath);
                                     // Scale watermark to be 15% of the target image width (proportional, larger)
                                     $targetWidth = (int) round($image->width() * 0.15);
                                     $watermark->scale(width: $targetWidth);
                                     
                                     // Insert watermark with proportional offset (e.g. 2.5% of width)
                                     $offset = (int) round($image->width() * 0.025);
                                     $image->insert($watermark, $offset, $offset, 'bottom-right');
                                 }
                                
                                // Generate filename with .webp extension
                                $filename = Str::uuid() . '.webp';
                                $path = 'news-images/' . $filename;
                                
                                // Ensure directory exists
                                $storagePath = storage_path('app/public/news-images');
                                if (!file_exists($storagePath)) {
                                    mkdir($storagePath, 0755, true);
                                }
                                
                                // Save as webp with 80% quality
                                $image->encode(new \Intervention\Image\Encoders\WebpEncoder(80))
                                      ->save(storage_path('app/public/' . $path));
                                
                                return $path;
                            })
                            ->required(),
                        TextInput::make('caption')
                            ->label('Caption')
                            ->placeholder('Masukkan caption untuk gambar ini...')
                            ->maxLength(255),
                    ])
                    ->columnSpanFull()
                    ->maxItems(5),
                RichEditor::make('content')
                    ->fileAttachmentsDisk('public')
                    ->fileAttachmentsDirectory('news-images')
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
