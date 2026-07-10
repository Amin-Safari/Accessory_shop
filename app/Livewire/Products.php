<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class Products extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url(history: true)]
    public $category = '';

    #[Url(history: true)]
    public $min_price = '';

    #[Url(history: true)]
    public $max_price = '';

    #[Url(history: true)]
    public $sort = 'newest';

    #[Url(history: true)]
    public $in_stock = false;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategory()
    {
        $this->resetPage();
    }

    public function updatedMinPrice()
    {
        $this->resetPage();
    }

    public function updatedMaxPrice()
    {
        $this->resetPage();
    }

    public function updatedSort()
    {
        $this->resetPage();
    }

    public function updatedInStock()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'category', 'min_price', 'max_price', 'sort', 'in_stock']);
        $this->resetPage();
    }

    public function render()
    {
        $categories = Category::active()
            ->withCount(['products' => fn($q) => $q->active()])
            ->orderBy('name')
            ->get();

        $priceRange = Product::active()
            ->selectRaw('MIN(price) as min_price, MAX(price) as max_price')
            ->first();

        $products = Product::query()
            ->with('category')
            ->active()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->category, function ($query) {
                $query->where('category_id', $this->category);
            })
            ->when($this->min_price, function ($query) {
                $query->where('price', '>=', $this->min_price);
            })
            ->when($this->max_price, function ($query) {
                $query->where('price', '<=', $this->max_price);
            })
            ->when($this->in_stock, function ($query) {
                $query->where('total', '>', 0);
            })
            ->when($this->sort, function ($query) {
                match ($this->sort) {
                    'newest' => $query->latest(),
                    'cheapest' => $query->orderBy('price', 'asc'),
                    'expensive' => $query->orderBy('price', 'desc'),
                    default => $query->latest(),
                };
            })
            ->paginate(12);

        return view('livewire.products', [
            'products' => $products,
            'categories' => $categories,
            'priceRange' => $priceRange,
        ]);
    }
}
