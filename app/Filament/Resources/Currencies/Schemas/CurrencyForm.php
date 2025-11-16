<?php

namespace App\Filament\Resources\Currencies\Schemas;

use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;

class CurrencyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Info Mata Uang')
                ->schema([
                    TextInput::make('code')
                        ->label('Kode')
                        ->required()
                        ->maxLength(3)
                        ->unique(ignoreRecord: true),

                    TextInput::make('name')
                        ->label('Nama')
                        ->required()
                        ->maxLength(100),

                    TextInput::make('symbol')
                        ->label('Simbol')
                        ->maxLength(5)
                        ->helperText('Contoh: Rp, $, €'),
                ])
                ->columns(3),

            Section::make('Pengaturan Kurs')
                ->schema([
                    TextInput::make('exchange_rate')
                        ->label('Kurs terhadap base (IDR)')
                        ->numeric()
                        ->required()
                        ->step('0.00000001')
                        ->helperText('Contoh: IDR = 1, USD ≈ 0.000065'),

                    Toggle::make('is_default')
                        ->label('Sebagai mata uang dasar')
                        ->helperText('Hanya boleh satu mata uang dasar.'),

                    Toggle::make('is_active')
                        ->label('Aktif')
                        ->default(true),
                ])
                ->columns(3),
        ]);
    }
}