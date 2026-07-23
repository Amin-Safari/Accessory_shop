<?php

use Livewire\Component;

new class extends Component
{
    public function logout()
    {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();

        return $this->redirect(route('home'), navigate: true);
    }
    public function render()
    {
        $unreadCount = auth()->user()->unreadNotificationsCount ?? 0;

        return view('components.layout.account.⚡sidebar.sidebar', [
            'unreadCount' => $unreadCount
        ]);
    }
};
