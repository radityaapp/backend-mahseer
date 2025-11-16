<?php

namespace App\Services;

use App\Models\Currency;
use InvalidArgumentException;

class CurrencyConverter
{
    public function convert(float|int $amount, string $fromCode, string $toCode): float
    {
        $fromCode = strtoupper($fromCode);
        $toCode   = strtoupper($toCode);

        $from = Currency::where('code', $fromCode)->first();
        $to   = Currency::where('code', $toCode)->first();

        if (! $from || ! $to) {
            throw new InvalidArgumentException('Kode mata uang tidak dikenal.');
        }

        $amountInBase = $amount / (float) $from->exchange_rate;
        $converted    = $amountInBase * (float) $to->exchange_rate;

        return $converted;
    }

    public function format(float|int $amount, string $code): string
    {
        $currency = Currency::where('code', strtoupper($code))->first();

        $symbol = $currency?->symbol ?? '';

        $decimals = config('currency.format.decimals');
        $decSep   = config('currency.format.decimal_separator');
        $thouSep  = config('currency.format.thousands_separator');

        return trim($symbol . ' ' . number_format($amount, $decimals, $decSep, $thouSep));
    }

    public function pricesForProduct(float|int $baseAmount): array
    {
        $baseCode   = config('currency.base_currency', 'IDR');
        $currencies = Currency::where('is_active', true)->get();

        $result = [];

        foreach ($currencies as $currency) {
            $converted = $this->convert($baseAmount, $baseCode, $currency->code);

            $result[$currency->code] = [
                'raw'   => $converted,
                'label' => $this->format($converted, $currency->code),
            ];
        }

        return $result;
    }
}