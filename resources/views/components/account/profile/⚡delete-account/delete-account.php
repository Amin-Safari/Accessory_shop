<?php

use Livewire\Component;

new class extends Component
{
    public $confirmation_text = '';
    public $showModal = false;

    protected $rules = [
        'confirmation_text' => 'required|string|in:حذف',
    ];

    protected $messages = [
        'confirmation_text.required' => 'لطفاً کلمه "حذف" را وارد کنید.',
        'confirmation_text.in' => 'لطفاً دقیقاً کلمه "حذف" را وارد کنید.',
    ];

    public function openModal()
    {
        $this->resetValidation();
        $this->confirmation_text = '';
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetValidation();
    }

    public function deleteAccount()
    {
        $this->validate();

        $user = auth()->user();

        // Soft delete or anonymize user data
        $user->delete();

        // Logout user
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();

        return $this->redirect(route('home'), navigate: true);
    }
};
