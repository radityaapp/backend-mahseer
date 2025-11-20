<?php

namespace App\Filament\Resources\Articles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;

class ArticlesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('featured_image')
                    ->label('Gambar Utama')
                    ->collection('featured_image')
                    ->square(),
                
                TextColumn::make('title')
                    ->label('Judul')
                    ->limit(40)
                    ->searchable(),

                TextColumn::make('author')
                    ->label('Penulis')
                    ->sortable()
                    ->toggleable(),

                IconColumn::make('is_published')
                    ->label('Status')
                    ->boolean(),

                TextColumn::make('published_at')
                    ->label('Tanggal Terbit')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_published')
                    ->label('Status Terbit')
                    ->boolean(),

                Filter::make('published_recently')
                    ->label('Diterbitkan Baru-baru Ini')
                    ->query(fn (Builder $query): Builder =>
                        $query->where('published_at', '>=', now()->subDays(30))
                    ),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}