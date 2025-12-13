<?php

namespace App\Filament\Resources\Activities\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ActivityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Aktifitas')
                        ->columns(1)
                        ->schema([
                            TextInput::make('external_url')
                                ->label('Eksternal URL (opsional)')
                                ->url()
                                ->nullable(),

                            TextInput::make('order')
                                ->label('Order/Urutan')
                                ->numeric()
                                ->default(0),

                            Toggle::make('is_active')
                                ->label('Status Aktif')
                                ->default(true),
                                
                            SpatieMediaLibraryFileUpload::make('featured_image')
                                ->label('Gambar Utama')
                                ->collection('featured_image')
                                ->image()
                                ->required(),
                        ]),
                        
            Section::make('Konten')
                ->schema([
                    Tabs::make('Translations')
                        ->tabs([
                            Tab::make('Indonesia')
                                ->schema([
                                    TextInput::make('title.id')
                                        ->label('Judul (ID)')
                                        ->required(),
                                    RichEditor::make('description.id')
                                        ->label('Deskripsi (ID)')
                                        ->columnSpanFull(),
                                ]),

                            Tab::make('English')
                                ->schema([
                                    TextInput::make('title.en')
                                        ->label('Title (EN)')
                                        ->required(),
                                    RichEditor::make('description.en')
                                        ->label('Description (EN)')
                                        ->columnSpanFull(),
                                ]),
                        ]),
                ]),
        ]);
    }
}