<?php

namespace App\Console\Commands\Concerns;

use App\Models\CronLog;

trait LogsCronRun
{
    protected function cronStart(string $name): \Illuminate\Support\Carbon
    {
        $startedAt = now();

        CronLog::updateOrCreate(
            ['name' => $name],
            ['status' => 'running', 'message' => null, 'started_at' => $startedAt, 'finished_at' => null]
        );

        return $startedAt;
    }

    protected function cronFinish(string $name, \Illuminate\Support\Carbon $startedAt, string $status, string $message, array $counts = []): void
    {
        CronLog::updateOrCreate(
            ['name' => $name],
            array_merge($counts, [
                'status' => $status,
                'message' => $message,
                'started_at' => $startedAt,
                'finished_at' => now(),
            ])
        );
    }
}