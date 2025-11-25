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
        $codes = Currency::where('is_active', true)->pluck('code')->all();

        if (empty($codes)) {
            $this->info('Tidak ada mata uang yang aktif.');
            return self::SUCCESS;
        }

        if (! in_array($base, $codes, true)) {
            $codes[] = $base;
        }

        $symbols = implode(',', $codes);

        $response = Http::get(config('services.openexchangerates.url'), [
            'app_id'  => config('services.openexchangerates.app_id'),
            'symbols' => $symbols,
        ]);

        if (! $response->ok()) {
            $this->error('Gagal memperbarui nilai tukar mata uang: '.$response->status());
            return self::FAILURE;
        }

        $rates = $response->json('rates', []);

        if (! isset($rates[$base])) {
            $this->error("Base currency [{$base}] tidak ditemukan di response API.");
            return self::FAILURE;
        }

        $rateBase = (float) $rates[$base];

        foreach ($codes as $code) {
            if ($code === $base) {
                Currency::where('code', $code)->update([
                    'exchange_rate' => 1.0,
                ]);
                continue;
            }

            if (! isset($rates[$code])) {
                $this->warn("Kode mata uang [{$code}] tidak ada di response API, dilewati.");
                continue;
            }

            $exchangeRate = (float) $rates[$code] / $rateBase;

            Currency::where('code', $code)->update([
                'exchange_rate' => $exchangeRate,
            ]);
        }

        $this->info('Update nilai tukar mata uang sukses.');

        return self::SUCCESS;
    }
}