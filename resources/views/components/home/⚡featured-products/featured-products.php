<?php

use App\Models\Product;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

new class extends Component {

    #[Computed]
    public function featuredProducts()
    {
        $data = Cache::remember('home_featured_products', now()->addHour(), function () {
            return Product::query()
                ->with('category:id,name')
                ->where('is_active', true)
                ->where('discount', '>', 0)
                ->orderBy('discount', 'desc')
                ->select('id', 'category_id', 'name', 'slug', 'images', 'price', 'discount', 'total')
                ->take(4)
                ->get()
                ->toArray();
        });

        return collect($data)->map(function ($item) {
            $item['category'] = (object) $item['category'];
            return (object) $item;
        });
    }
};
