<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Info Kategori')
                ->columns(2)
                ->schema([
                    Select::make('type')
                        ->label('Tipe')
                        ->options([
                            'product' => 'Produk',
                        ])
                        ->default('Produk')
                        ->required(),

                    TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),
                ]),

            Section::make('Terjemahan Nama Kategori')
                ->schema([
                    Tabs::make('Translations')
                        ->tabs([
                            Tab::make('Indonesia')
                                ->schema([
                                    TextInput::make('name.id')
                                        ->label('Nama (ID)')
                                        ->required(),
                                ]),

                            Tab::make('English')
                                ->schema([
                                    TextInput::make('name.en')
                                        ->label('Name (EN)')
                                        ->required(),
                                ]),
                        ]),
                ]),
        ]);
    }
}