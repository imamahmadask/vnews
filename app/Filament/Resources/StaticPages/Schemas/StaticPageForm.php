<?php

namespace App\Filament\Resources\StaticPages\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class StaticPageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, callable $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true),
                Textarea::make('lead_text')
                    ->label('Lead Paragraph')
                    ->helperText('Paragraph pengantar tebal yang berada di awal halaman.')
                    ->rows(3)
                    ->columnSpanFull(),
                RichEditor::make('content')
                    ->label('Content / Body')
                    ->helperText('Konten utama halaman.')
                    ->fileAttachmentsDisk('public')
                    ->fileAttachmentsDirectory('pages-images')
                    ->columnSpanFull(),
                TextInput::make('contact_email')
                    ->label('Contact Email')
                    ->email()
                    ->placeholder('redaksi@vnews.id')
                    ->columnSpanFull(),
                Textarea::make('contact_address')
                    ->label('Contact Address')
                    ->placeholder('Kota Mataram, Nusa Tenggara Barat, Indonesia')
                    ->rows(2)
                    ->columnSpanFull(),
            ]);
    }
}
