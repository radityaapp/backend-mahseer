<?php

namespace App\Console\Commands;

use App\Models\Currency;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class UpdateCurrencyRates extends Command
{
    protected $signature = 'currency:update';
    protected $description = 'Memperbarui nilai tukar mata uang dari sumber eksternal';

    public function handle(): int
    {
        $base = config('currency.base_currency', 'IDR');

        $symbols = Currency::where('is_active', true)
            ->where('code', '!=', $base)
            ->pluck('code')
            ->implode(',');

        if ($symbols === '') {
            $this->info('Tidak ada mata uang yang perlu diperbarui.');
            return self::SUCCESS;
        }

        $response = Http::get('https://api.exchangerate.host/latest', [
            'base'    => $base,
            'symbols' => $symbols,
        ]);

        if (! $response->ok()) {
            $this->error('Failed to fetch rates: '.$response->status());
            return self::FAILURE;
        }

        $rates = $response->json('rates', []);

        foreach ($rates as $code => $exchangeRate) {
            Currency::where('code', $code)->update([
                'exchange_rate' => $exchangeRate,
            ]);
        }

        $this->info('Update nilai tukar mata uang sukses.');

        return self::SUCCESS;
    }
}