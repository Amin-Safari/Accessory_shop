<?php

namespace App\Livewire;

use Livewire\Component;

class Checkout extends Component
{
    public int $currentStep = 1;

    protected $queryString = [
        'currentStep' => ['as' => 'step', 'except' => 1],
    ];

    protected $listeners = [
        'nextStep' => 'goToNextStep',
        'previousStep' => 'goToPreviousStep',
        'orderCreated' => 'handleOrderCreated',
    ];

    public function mount(): void
    {
        if (session()->has('last_order_uuid') || session()->has('payment_error')) {
            $this->currentStep = 4;
            return;
        }

        $step = request()->query('step');
        if ($step && in_array((int)$step, [1, 2, 3, 4])) {
            $this->currentStep = (int)$step;
        }
    }

    public function goToNextStep(): void
    {
        if ($this->currentStep < 4) {
            $this->currentStep++;
        }
    }

    public function goToPreviousStep(): void
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function handleOrderCreated($orderId): void
    {
        // Order created successfully, nothing else needed
    }

    public function getIsLoggedInProperty(): bool
    {
        return auth()->check();
    }

    public function render()
    {
        return view('livewire.checkout');
    }
}
