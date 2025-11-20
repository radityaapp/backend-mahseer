<?php

namespace App\Filament\Resources\Activities\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
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
                            SpatieMediaLibraryFileUpload::make('activity_image')
                                ->label('Gambar Aktifitas')
                                ->collection('activity_image')
                                ->image()
                                ->required(),

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
            ])
        ]);
    }
}