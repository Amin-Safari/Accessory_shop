<?php

namespace App\Livewire\User;

use Livewire\Component;

class SignUp extends Component
{
    public function render()
    {
        return view('livewire.user.sign-up')
            ->layout('layouts::sign');
    }
}
