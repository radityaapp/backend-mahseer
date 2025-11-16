<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Currency extends Model
{
    use HasFactory;

    protected $table = 'currencies';

    protected $fillable = [
        'code',
        'name',
        'symbol',
        'exchange_rate',
        'is_default',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'exchange_rate' => 'decimal:8',
            'is_default' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public static function base(): ?self
    {
        return static::where('is_default', true)->first();
    }

    protected static function booted(): void
{
    static::saving(function (Currency $currency) {
        if ($currency->is_default) {
            static::where('id', '!=', $currency->id)->update(['is_default' => false]);
        }
    });
}
}