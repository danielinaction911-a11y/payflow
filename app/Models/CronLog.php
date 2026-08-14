<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CronLog extends Model
{
    protected $fillable = [
        'name', 'status', 'processed', 'completed',
        'skipped', 'failed', 'message', 'started_at', 'finished_at',
    ];

    protected function casts(): array
    {
        return [
            'processed' => 'integer',
            'completed' => 'integer',
            'skipped' => 'integer',
            'failed' => 'integer',
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
        ];
    }

    public function durationInSeconds(): ?int
    {
        if (! $this->started_at || ! $this->finished_at) {
            return null;
        }

        return $this->finished_at->diffInSeconds($this->started_at);
    }
}