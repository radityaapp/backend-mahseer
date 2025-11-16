<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Schemas\Schema;
use App\Models\Category;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Info Produk')
                ->columns(2)
                ->schema([
                    Select::make('category_id')
                        ->relationship('category', 'name')
                        ->options(Category::all()->pluck('name', 'id'))
                        ->label('Kategori')
                        ->required()
                        ->preload()
                        ->searchable(),

                    TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),

                    TextInput::make('price')
                        ->label('Harga')
                        ->numeric()
                        ->required()
                        ->prefix('Rp'),

                    TextInput::make('stock')
                        ->label('Stok')
                        ->numeric()
                        ->required()
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
                            Tabs\Tab::make('Indonesia')
                                ->schema([
                                    TextInput::make('name.id')
                                        ->label('Nama (ID)')
                                        ->required(),

                                    Textarea::make('general_information.id')
                                        ->label('Informasi Umum (ID)')
                                        ->rows(3),

                                    Textarea::make('description.id')
                                        ->label('Deskripsi (ID)')
                                        ->rows(5),
                                ]),
                            Tabs\Tab::make('English')
                                ->schema([
                                    TextInput::make('name.en')
                                        ->label('Name (EN)')
                                        ->required(),

                                    Textarea::make('general_information.en')
                                        ->label('General Information (EN)')
                                        ->rows(3),

                                    Textarea::make('description.en')
                                        ->label('Description (EN)')
                                        ->rows(5),
                                ]),
                        ]),
                ]),

            Section::make('Gambar Produk')
                ->schema([
                    SpatieMediaLibraryFileUpload::make('product_images')
                        ->collection('product_images')
                        ->label('Gambar Produk')
                        ->multiple()
                        ->reorderable()
                        ->image()
                        ->panelLayout('grid')
                        ->columnSpanFull(),
                ]),
        ]);
    }
}