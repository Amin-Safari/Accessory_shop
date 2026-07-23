<?php

namespace App\Livewire\Account;

use Livewire\Component;

class Transactions extends Component
{
    public function render()
    {
        return view('livewire.account.transactions')
            ->layout('layouts.account', ['title' => 'تراکنش ها']);
    }
}
