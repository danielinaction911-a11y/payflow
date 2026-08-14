<?php

namespace App\Console\Commands;

use App\Console\Commands\Concerns\LogsCronRun;
use App\Models\TradingPair;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SyncTradingPairPrices extends Command
{
    use LogsCronRun;

    protected $signature = 'trading:sync-prices';
    protected $description = 'Sync current prices for all trading pairs from CoinGecko';

    protected string $cronName = 'trading:sync-prices';

    public function handle(): int
    {
        $startedAt = $this->cronStart($this->cronName);

        $pairs = TradingPair::with('baseCurrency')->get();
        $ids = $pairs->pluck('baseCurrency.coingecko_id')->filter()->unique()->implode(',');

        if (! $ids) {
            $this->cronFinish($this->cronName, $startedAt, 'success', 'No trading pairs with coingecko_id to sync.', [
                'processed' => 0, 'completed' => 0, 'skipped' => 0, 'failed' => 0,
            ]);
            return self::SUCCESS;
        }

        $response = Http::get('https://api.coingecko.com/api/v3/simple/price', [
            'ids' => $ids,
            'vs_currencies' => 'usd',
            'include_24hr_change' => 'true',
        ]);

        if (! $response->ok()) {
            $this->error('Failed to fetch prices from CoinGecko.');

            $this->cronFinish($this->cronName, $startedAt, 'failed', 'CoinGecko request failed with status ' . $response->status(), [
                'processed' => 0, 'completed' => 0, 'skipped' => 0, 'failed' => $pairs->count(),
            ]);

            return self::FAILURE;
        }

        $data = $response->json();
        $updated = 0;
        $skipped = 0;

        foreach ($pairs as $pair) {
            $coingeckoId = $pair->baseCurrency->coingecko_id;

            if (! $coingeckoId || ! isset($data[$coingeckoId])) {
                $skipped++;
                continue;
            }

            $pair->update([
                'current_price' => $data[$coingeckoId]['usd'] ?? $pair->current_price,
                'change_24h_percent' => $data[$coingeckoId]['usd_24h_change'] ?? $pair->change_24h_percent,
            ]);

            $updated++;
        }

        $this->cronFinish($this->cronName, $startedAt, 'success', "Updated {$updated} pair(s). Skipped {$skipped}.", [
            'processed' => $pairs->count(), 'completed' => $updated, 'skipped' => $skipped, 'failed' => 0,
        ]);

        $this->info('Trading pair prices synced.');

        return self::SUCCESS;
    }
}