<?php

namespace App\Filament\Resources\Articles\Schemas;

use App\Models\User;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;

class ArticleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Meta & Status')
                ->columns(2)
                ->schema([
                    Select::make('created_by')
                        ->label('Dibuat Oleh')
                        ->options(fn () => User::query()
                            ->orderBy('name')
                            ->pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->nullable(),

                    TextInput::make('author')
                        ->label('Nama Penulis')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255)
                        ->helperText('Otomatis diisi dari Judul (ID), tapi bisa diubah manual.'),

                    Toggle::make('is_published')
                        ->label('Status')
                        ->default(false),

                    DateTimePicker::make('published_at')
                        ->label('Tanggal Terbit')
                        ->seconds(false)
                        ->nullable()
                        ->helperText('Optional, bisa dikosongkan untuk draft.'),
                ]),

            Section::make('Konten')
                ->schema([
                    Tabs::make('Translations')
                        ->tabs([
                            Tab::make('Indonesia')
                                ->schema([
                                    TextInput::make('title.id')
                                        ->label('Judul (ID)')
                                        ->required()
                                        ->live(debounce: 500)
                                        ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                        if (!filled($get('slug')) && filled($state)) {
                                            $set('slug', Str::slug($state));
                                        }
                                    }),

                                    Textarea::make('excerpt.id')
                                        ->label('Ringkasan (ID)')
                                        ->rows(3),

                                    RichEditor::make('content.id')
                                        ->label('Konten (ID)')
                                        ->columnSpanFull(),
                                ]),

                            Tab::make('English')
                                ->schema([
                                    TextInput::make('title.en')
                                        ->label('Title (EN)')
                                        ->required(),

                                    Textarea::make('excerpt.en')
                                        ->label('Excerpt (EN)')
                                        ->rows(3),

                                    RichEditor::make('content.en')
                                        ->label('Content (EN)')
                                        ->columnSpanFull(),
                                ]),
                        ]),
                ]),

            Section::make('Gambar Unggulan')
                ->schema([
                    SpatieMediaLibraryFileUpload::make('featured_image')
                                ->label('Gambar Utama')
                                ->collection('featured_image')
                                ->image()
                                ->required(),

                    SpatieMediaLibraryFileUpload::make('content_images')
                                ->label('Gambar Konten')
                                ->collection('content_images')
                                ->image()
                                ->multiple()
                                ->reorderable()
                                ->helperText('Gambar-gambar tambahan yang dipakai di dalam artikel'),
                ]),
        ]);
    }
}