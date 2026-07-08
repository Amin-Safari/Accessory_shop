<?php

use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    #[Computed]
    public function categories()
    {
        $data = Cache::remember('home_categories', now()->addHour(), function () {
            return Category::query()
                ->where('is_active', true)
                ->select('id', 'name', 'slug', 'image')
                ->withCount([
                    'products' => fn ($q) => $q->where('is_active', true),
                ])
                ->take(5)
                ->get()
                ->toArray();
        });

        return collect($data)->map(fn ($item) => (object) $item);
    }
};
