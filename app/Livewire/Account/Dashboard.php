<?php

namespace App\Livewire\Account;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.account.dashboard')
            ->layout('layouts.account', ['title' => 'داشبورد']);
    }
}
