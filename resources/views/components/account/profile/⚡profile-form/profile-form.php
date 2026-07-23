<?php

use App\Models\City;
use App\Models\Province;
use Livewire\Component;

new class extends Component
{
    public $name;
    public $address;
    public $postal_code;
    public $province_id;
    public $city_id;
    public $cities = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'address' => 'nullable|string|max:500',
        'postal_code' => 'nullable|string|max:10',
        'province_id' => 'nullable|exists:provinces,id',
        'city_id' => 'nullable|exists:cities,id',
    ];

    protected $messages = [
        'name.required' => 'نام و نام خانوادگی الزامی است.',
        'name.max' => 'نام و نام خانوادگی نمی‌تواند بیشتر از ۲۵۵ کاراکتر باشد.',
        'address.max' => 'آدرس نمی‌تواند بیشتر از ۵۰۰ کاراکتر باشد.',
        'postal_code.max' => 'کد پستی نمی‌تواند بیشتر از ۱۰ کاراکتر باشد.',
        'province_id.exists' => 'استان انتخاب شده معتبر نیست.',
        'city_id.exists' => 'شهر انتخاب شده معتبر نیست.',
    ];

    public function mount()
    {
        $user = auth()->user();
        $this->name = $user->name;
        $this->address = $user->address;
        $this->postal_code = $user->postal_code;
        $this->province_id = $user->province_id;
        $this->city_id = $user->city_id;

        // Load cities if province is selected
        if ($this->province_id) {
            $this->cities = City::where('province_id', $this->province_id)->get();
        }
    }

    public function updatedProvinceId($value)
    {
        $this->city_id = null;
        $this->cities = [];

        if ($value) {
            $this->cities = City::where('province_id', $value)
                ->orderBy('name')
                ->get();
        }
    }

    public function updateProfile()
    {
        $this->validate();

        auth()->user()->update([
            'name' => $this->name,
            'address' => $this->address,
            'postal_code' => $this->postal_code,
            'province_id' => $this->province_id,
            'city_id' => $this->city_id,
        ]);

        session()->flash('profile_updated', 'پروفایل شما با موفقیت به‌روزرسانی شد.');

        $this->dispatch('profile-updated');
    }

    public function render()
    {
        $provinces = Province::orderBy('name')->get();

        return view('components.account.profile.⚡profile-form.profile-form', [
            'provinces' => $provinces
        ]);
    }
};
