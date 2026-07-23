<?php

namespace App\Livewire\Account;

use Livewire\Component;

class Favorites extends Component
{
    public function render()
    {
        return view('livewire.account.favorites')
            ->layout('layouts.account', ['title' => 'علاقه مندی ها']);
    }
}
