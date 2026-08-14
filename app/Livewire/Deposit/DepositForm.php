<?php

namespace App\Livewire\Deposit;

use App\Enums\TransactionDirection;
use App\Enums\TransactionType;
use App\Models\Deposit;
use App\Models\Gateway;
use App\Services\TransactionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Traits\HandlesFileUploads;

#[Layout('components.layouts.app')]
class DepositForm extends Component
{
    use WithFileUploads, HandlesFileUploads;

    public string $step = 'select'; // select -> fields -> success

    public ?int $gatewayId = null;
    public string $amount = '';
    public array $fields = [];
    public array $uploads = [];

    public ?array $successData = null;

    public bool $accessBlocked = false;
    public string $blockedMessage = '';

    #[Computed]
    public function gateways()
    {
        return Gateway::where('status', true)->orderBy('id')->get();
    }

    #[Computed]
    public function selectedGateway()
    {
        return $this->gatewayId ? Gateway::find($this->gatewayId) : null;
    }

    #[Computed]
    public function fee(): float
    {
        if (! $this->selectedGateway || ! is_numeric($this->amount)) {
            return 0;
        }

        return $this->selectedGateway->calculateFee((float) $this->amount);
    }

    #[Computed]
    public function total(): float
    {
        if (! is_numeric($this->amount)) {
            return 0;
        }

        return (float) $this->amount + $this->fee;
    }

    public function mount(): void
    {
        $this->checkAccess();

        if ($this->accessBlocked) {
            return;
        }

        $this->gatewayId = $this->gateways->first()?->id;
    }

    protected function checkAccess(): void
    {
        $user = Auth::user();

        // 1. Global deposits toggle
        if (! (bool) setting('deposits_enabled', true)) {
            $this->accessBlocked = true;
            $this->blockedMessage = 'Deposits are currently disabled on the platform. Please try again later.';
            return;
        }

        // 2. Per-user deposit restriction
        if ($user->deposit_status === 'disabled') {
            $this->accessBlocked = true;
            $this->blockedMessage = $user->deposit_message ?: 'Deposits are currently disabled for your account. Please contact support.';
            return;
        }

        $this->accessBlocked = false;
        $this->blockedMessage = '';
    }

    public function selectGateway(int $id): void
    {
        $this->gatewayId = $id;
        $this->fields = [];
        $this->uploads = [];
    }

    public function setAmount(string $amount): void
    {
        $this->amount = $amount;
    }

    public function proceedToFields(): void
    {
        $this->checkAccess();
        if ($this->accessBlocked) {
            return;
        }
        $gateway = $this->selectedGateway;

        $this->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        if (! $gateway) {
            $this->addError('gatewayId', 'Please select a payment method.');
            return;
        }

        if ((float) $this->amount < (float) $gateway->min_amount || (float) $this->amount > (float) $gateway->max_amount) {
            $this->addError('amount', 'Amount must be between ' . money_format($gateway->min_amount, $gateway->currency) . ' and ' . money_format($gateway->max_amount, $gateway->currency) . '.');
            return;
        }

        $this->step = 'fields';
    }

    public function backToSelect(): void
    {
        $this->step = 'select';
    }

    public function submit(): void
    {
        // final checkpoint right before funds/records are created
        $this->checkAccess();
        if ($this->accessBlocked) {
            $this->step = 'select';
            $this->addError('submit', $this->blockedMessage);
            return;
        }

        $gateway = $this->selectedGateway;

        if (! $gateway) {
            return;
        }

        $rules = [];

        if ($gateway->payment_fields) {
            foreach ($gateway->payment_fields as $field) {
                $name = $field['name'];
                $required = ! empty($field['required']) ? 'required' : 'nullable';

                if ($field['type'] === 'file') {
                    $rules["uploads.$name"] = "$required|file|mimes:jpg,jpeg,png,pdf|max:2048";
                } else {
                    $rules["fields.$name"] = "$required|string|max:500";
                }
            }
        }

        $this->validate($rules);

        $uploadedPaths = [];

        try {
            $fieldData = $this->fields;

            if ($gateway->payment_fields) {
                foreach ($gateway->payment_fields as $field) {
                    if ($field['type'] === 'file' && isset($this->uploads[$field['name']])) {
                        $path = $this->uploadFile(
                            $this->uploads[$field['name']],
                            'images/deposits',
                            null,
                            'deposit_' . Auth::id() . '_' . $field['name']
                        );

                        $fieldData[$field['name']] = $path;
                        $uploadedPaths[] = $path;
                    }
                }
            }

            DB::beginTransaction();

            $reference = 'DEP-' . strtoupper(Str::random(10));

            $transaction = app(TransactionService::class)->create([
                'user_id' => Auth::id(),
                'reference' => $reference,
                'amount' => (float) $this->amount,
                'fee' => $this->fee,
                'currency' => $gateway->currency,
                'type' => TransactionType::Deposit,
                'direction' => TransactionDirection::Credit,
                'description' => "Deposit via {$gateway->name}",
                'status' => \App\Enums\TransactionStatus::Pending,
                'metadata' => [
                    'gateway_id' => $gateway->id,
                    'gateway_name' => $gateway->name,
                    'total' => $this->total,
                ],
            ]);

            Deposit::create([
                'user_id' => Auth::id(),
                'amount' => (float) $this->amount,
                'fee' => $this->fee,
                'currency' => $gateway->currency,
                'method' => $gateway->name,
                'transaction_id' => $transaction->reference,
                'status' => 'pending',
                'metadata' => $fieldData,
            ]);

            DB::commit();

            $this->successData = [
                'amount' => $this->amount,
                'fee' => $this->fee,
                'total' => $this->total,
                'gateway' => $gateway->name,
                'reference' => $transaction->reference,
            ];

            $this->step = 'success';
        } catch (\Throwable $e) {
            DB::rollBack();

            foreach ($uploadedPaths as $path) {
                $this->deleteFile($path);
            }

            report($e);

            $this->addError('submit', 'Something went wrong while processing your deposit. Please try again.');
        }
    }

    public function startOver(): void
    {
        $this->reset(['step', 'gatewayId', 'amount', 'fields', 'uploads', 'successData']);
        $this->checkAccess();

        if (! $this->accessBlocked) {
            $this->gatewayId = $this->gateways->first()?->id;
        }

        $this->step = 'select';
    }

    public function render()
    {
        return view('livewire.deposit.deposit-form')->layout('components.layouts.app', [
            'title' => 'Deposit Funds',
        ]);
    }
}