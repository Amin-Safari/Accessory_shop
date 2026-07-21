<?php

use Livewire\Component;
use App\Models\Province;
use App\Models\City;

new class extends Component
{
    public string $name = '';
    public string $phone = '';
    public string $address = '';
    public string $city = '';
    public string $province = '';
    public string $postal_code = '';
    public string $notes = '';
    public array $provinces = [];
    public array $cities = [];
    public bool $isLoading = false;

    public function mount(): void
    {
        $this->loadProvinces();
        $this->loadUserInfo();
    }

    public function loadProvinces(): void
    {
        $this->provinces = Province::
            orderBy('name')
            ->get()
            ->toArray();
    }

    public function loadCities(): void
    {
        if (!empty($this->province)) {
            $this->cities = City::where('province_id', $this->province)
                ->orderBy('name')
                ->get()
                ->toArray();

            // اگر شهر انتخاب شده در استان جدید وجود ندارد، ریست شود
            if (!in_array($this->city, array_column($this->cities, 'id'))) {
                $this->city = '';
            }
        } else {
            $this->cities = [];
            $this->city = '';
        }
    }

    public function updatedProvince(): void
    {
        $this->loadCities();
    }

    public function loadUserInfo(): void
    {
        $user = Auth::user();

        if ($user) {
            $this->name = $user->name ?? '';
            $this->phone = $user->phone ?? '';
            $this->address = $user->address ?? '';
            $this->postal_code = $user->postal_code ?? '';

            // بارگذاری اطلاعات استان و شهر کاربر
            if ($user->city_id) {
                $city = City::find($user->city_id);
                if ($city) {
                    $this->province = $city->province_id;
                    $this->city = $city->id;
                    $this->loadCities();
                }
            }
        }
    }

    public function saveAndContinue(): void
    {
        $this->validate([
            'name' => 'required|string|min:2|max:100',
            'phone' => 'required|string|regex:/^09[0-9]{9}$/',
            'address' => 'required|string|min:10|max:500',
            'province' => 'required|exists:provinces,id',
            'city' => 'required|exists:cities,id',
            'postal_code' => 'required|string|size:10',
            'notes' => 'nullable|string|max:500',
        ], [
            'name.required' => 'نام الزامی است',
            'phone.required' => 'شماره تماس الزامی است',
            'phone.regex' => 'فرمت شماره تماس صحیح نیست',
            'address.required' => 'آدرس الزامی است',
            'address.min' => 'آدرس باید حداقل ۱۰ کاراکتر باشد',
            'province.required' => 'استان الزامی است',
            'city.required' => 'شهر الزامی است',
            'postal_code.required' => 'کد پستی الزامی است',
            'postal_code.size' => 'کد پستی باید ۱۰ رقم باشد',
        ]);

        // دریافت نام شهر و استان
        $cityModel = City::find($this->city);
        $provinceModel = Province::find($this->province);

        // ذخیره اطلاعات در سشن
        session()->put('checkout.customer_info', [
            'name' => $this->name,
            'phone' => $this->phone,
            'address' => $this->address,
            'province_id' => $this->province,
            'province_name' => $provinceModel?->name,
            'city_id' => $this->city,
            'city_name' => $cityModel?->name,
            'postal_code' => $this->postal_code,
            'notes' => $this->notes,
        ]);

        // بروزرسانی اطلاعات کاربر
        $user = Auth::user();
        if ($user) {
            $user->update([
                'name' => $this->name,
                'address' => $this->address,
                'province_id' => $this->province,
                'city_id' => $this->city,
                'postal_code' => $this->postal_code,
            ]);
        }

        $this->dispatch('nextStep');
    }

};
