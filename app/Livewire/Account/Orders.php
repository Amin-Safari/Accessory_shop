<?php

namespace App\Livewire\Account;

use Livewire\Component;

class Orders extends Component
{
    public function render()
    {
        return view('livewire.account.orders')
            ->layout('layouts.account', ['title' => 'سفارش ها']);
    }
}
