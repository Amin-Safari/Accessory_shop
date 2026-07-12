<?php

namespace App\Livewire;

use Livewire\Component;

class CheckOut extends Component
{
    public function mount()
    {
        if (!auth()->check()) {
            $this->dispatch('open-auth');
        }
    }

    public function render()
    {
        return view('livewire.check-out');
    }
}
