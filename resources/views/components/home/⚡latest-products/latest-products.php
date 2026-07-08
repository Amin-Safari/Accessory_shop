<?php

use App\Models\Product;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Illuminate\Support\Facades\Cache;

new class extends Component {
    #[Computed]
    public function latestProducts()
    {
        $data = Cache::remember('home_latest_products', now()->addHour(), function () {
            return Product::query()
                ->with('category:id,name')
                ->where('is_active', true)
                ->where('is_new', true)
                ->latest()
                ->select('id', 'category_id', 'name', 'slug', 'images', 'price', 'discount', 'total', 'is_new', 'created_at')
                ->take(8)
                ->get()
                ->toArray();
        });
        return collect($data)->map(function ($item) {
            $item['category'] = (object) $item['category'];
            return (object) $item;
        });
    }
};
