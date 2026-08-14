<?php

namespace App\Services;

use App\Enums\TransactionDirection;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Models\Transaction;
use Illuminate\Support\Str;

class TransactionService
{
    /**
     * Create a reusable transaction record.
     *
     * @param array<string, mixed> $data
     */
    public function create(array $data): Transaction
    {
        $defaults = [
            'reference' => 'trx-' . Str::random(12),
            'amount' => 0,
            'type' => TransactionType::Other,
            'direction' => TransactionDirection::Credit,
            'status' => TransactionStatus::Completed,
            'description' => null,
            'fee' => 0,
            'currency' => 'USD',
            'metadata' => [],
        ];

        $payload = array_merge($defaults, $data);

        if (empty($payload['reference'])) {
            $payload['reference'] = 'trx-' . Str::random(12);
        }

        if (! empty($payload['type']) && ! $payload['type'] instanceof TransactionType) {
            $payload['type'] = TransactionType::tryFrom((string) $payload['type']) ?? TransactionType::Other;
        }

        if (! empty($payload['direction']) && ! $payload['direction'] instanceof TransactionDirection) {
            $payload['direction'] = TransactionDirection::tryFrom((string) $payload['direction']) ?? TransactionDirection::Credit;
        }

        if (! empty($payload['status']) && ! $payload['status'] instanceof TransactionStatus) {
            $payload['status'] = TransactionStatus::tryFrom((string) $payload['status']) ?? TransactionStatus::Completed;
        }

        return Transaction::create($payload);
    }
}
