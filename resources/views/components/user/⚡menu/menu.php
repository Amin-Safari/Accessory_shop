<?php

namespace App\Livewire\User;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

new class extends Component {
    public function logout()
    {
        Auth::logout();

        $this->redirect('/', navigate: true);
    }


};
