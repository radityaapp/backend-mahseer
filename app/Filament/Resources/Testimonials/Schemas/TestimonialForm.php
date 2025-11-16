<?php

namespace App\Filament\Resources\Testimonials\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class TestimonialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Testimonial Info')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                        ->label('Nama')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('institution.id')
                        ->label('Institusi/Perusahaan (ID)')
                        ->maxLength(255),

                    TextInput::make('institution.en')
                        ->label('Institution (EN)')
                        ->maxLength(255),

                    TextInput::make('order')
                        ->label('Urutan')
                        ->numeric()
                        ->minValue(0)
                        ->default(0),

                    Toggle::make('is_active')
                        ->label('Status')
                        ->default(true),
                ]),

            Section::make('Konten')
                ->schema([
                    Tabs::make('Translations')
                        ->tabs([
                            Tab::make('Indonesia')
                                ->schema([
                                    Textarea::make('description.id')
                                        ->label('Deskripsi (ID)')
                                        ->rows(4)
                                        ->required(),
                                ]),
                            Tab::make('English')
                                ->schema([
                                    Textarea::make('description.en')
                                        ->label('Description (EN)')
                                        ->rows(4)
                                        ->required(),
                                ]),
                        ]),
                ]),

            Section::make('Avatar')
                ->schema([
                    SpatieMediaLibraryFileUpload::make('avatar')
                        ->collection('avatar')
                        ->label('Foto Profil')
                        ->image()
                        ->imageEditor()
                        ->required(false),
                ]),
        ]);
    }
}