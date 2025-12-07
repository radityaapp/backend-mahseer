<?php

namespace App\Services;

use App\Models\Currency;
use Illuminate\Support\Facades\Cache;
use InvalidArgumentException;

class CurrencyConverter
{
    protected function getRates(): array
    {
        $ttl = now()->addHours(26);

        return Cache::remember('currency:rates', $ttl, function () {
            return Currency::where('is_active', true)
                ->pluck('exchange_rate', 'code')
                ->map(fn ($rate) => (float) $rate)
                ->toArray();
        });
    }

    protected function getSymbols(): array
    {
        return Cache::rememberForever('currency:symbols', function () {
            return Currency::pluck('symbol', 'code')->toArray();
        });
    }

    protected function getRate(string $code): float
    {
        $code  = strtoupper($code);
        $rates = $this->getRates();

        if (! isset($rates[$code])) {
            throw new InvalidArgumentException("Kode mata uang tidak dikenal: {$code}");
        }

        $rate = (float) $rates[$code];

        if ($rate <= 0) {
            throw new InvalidArgumentException("Nilai kurs tidak valid untuk: {$code}");
        }

        return $rate;
    }

    public function convert(float|int $amount, string $fromCode, string $toCode): float
    {
        $fromCode = strtoupper($fromCode);
        $toCode   = strtoupper($toCode);

        $fromRate = $this->getRate($fromCode);
        $toRate   = $this->getRate($toCode);

        $amountInBase = $amount / $fromRate;
        $converted    = $amountInBase * $toRate;

        return $converted;
    }

    public function format(float|int $amount, string $code): string
    {
        $code     = strtoupper($code);
        $symbols  = $this->getSymbols();
        $symbol   = $symbols[$code] ?? '';

        $decimals = config('currency.format.decimals');
        $decSep   = config('currency.format.decimal_separator');
        $thouSep  = config('currency.format.thousands_separator');

        return trim($symbol . ' ' . number_format($amount, $decimals, $decSep, $thouSep));
    }

    public function pricesForProduct(float|int $baseAmount): array
    {
        $baseCode = strtoupper(config('currency.base_currency', 'IDR'));
        $rates    = $this->getRates();

        if (! isset($rates[$baseCode])) {
            throw new InvalidArgumentException("Kode mata uang tidak dikenal: {$baseCode}");
        }

        $result = [];

        foreach ($rates as $code => $rate) {
            $converted = $this->convert($baseAmount, $baseCode, $code);

            $result[$code] = [
                'raw'   => $converted,
                'label' => $this->format($converted, $code),
            ];
        }

        return $result;
    }
}