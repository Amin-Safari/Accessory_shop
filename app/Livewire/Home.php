<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Category;

class Home extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedCategories = [];
    public $priceRange = [0, 1000];
    public $sortBy = 'newest';
    public $viewMode = 'grid'; // grid or list
    public $showFilters = false;

    // Cart
    public $cart = [];
    public $cartTotal = 0;
    public $cartCount = 0;
    public $showCart = false;

    // Quick view
    public $quickViewProduct = null;

    // Wishlist
    public $wishlist = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'selectedCategories' => ['except' => []],
        'sortBy' => ['except' => 'newest'],
    ];

    public function mount()
    {
        $this->loadCartFromSession();
    }

    public function loadCartFromSession()
    {
        $this->cart = session()->get('cart', []);
        $this->calculateCartTotal();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSelectedCategories()
    {
        $this->resetPage();
    }

    public function updatedPriceRange()
    {
        $this->resetPage();
    }

    public function calculateCartTotal()
    {
        $this->cartTotal = array_sum(array_map(function($item) {
            return $item['price'] * $item['quantity'];
        }, $this->cart));

        $this->cartCount = array_sum(array_column($this->cart, 'quantity'));
    }

    public function addToCart($productId, $productName, $price, $image)
    {
        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['quantity']++;
        } else {
            $this->cart[$productId] = [
                'id' => $productId,
                'name' => $productName,
                'price' => $price,
                'image' => $image,
                'quantity' => 1
            ];
        }

        $this->calculateCartTotal();
        session()->put('cart', $this->cart);

        $this->dispatch('cart-updated', count: $this->cartCount);
        $this->dispatch('notify',
            message: "$productName به سبد خرید اضافه شد",
            type: 'success'
        );
    }

    public function removeFromCart($productId)
    {
        unset($this->cart[$productId]);
        $this->calculateCartTotal();
        session()->put('cart', $this->cart);
    }

    public function updateCartQuantity($productId, $quantity)
    {
        if ($quantity > 0) {
            $this->cart[$productId]['quantity'] = $quantity;
        } else {
            unset($this->cart[$productId]);
        }

        $this->calculateCartTotal();
        session()->put('cart', $this->cart);
    }

    public function toggleWishlist($productId)
    {
        if (in_array($productId, $this->wishlist)) {
            $this->wishlist = array_diff($this->wishlist, [$productId]);
            $message = "از علاقه‌مندی‌ها حذف شد";
        } else {
            $this->wishlist[] = $productId;
            $message = "به علاقه‌مندی‌ها اضافه شد";
        }

        $this->dispatch('notify', message: $message, type: 'info');
    }

    public function quickView($productId)
    {
        $this->quickViewProduct = Product::find($productId);
    }

    public function closeQuickView()
    {
        $this->quickViewProduct = null;
    }

    public function render()
    {
        $categories = Category::withCount('products')->get();

        $products = Product::query()
            ->when($this->search, function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->when($this->selectedCategories, function($query) {
                $query->whereIn('category_id', $this->selectedCategories);
            })
            ->when($this->priceRange, function($query) {
                $query->whereBetween('price', $this->priceRange);
            })
            ->when($this->sortBy, function($query) {
                match($this->sortBy) {
                    'price_asc' => $query->orderBy('price', 'asc'),
                    'price_desc' => $query->orderBy('price', 'desc'),
                    'name_asc' => $query->orderBy('name', 'asc'),
                    'popular' => $query->orderBy('sales_count', 'desc'),
                    default => $query->latest()
                };
            })
            ->where('is_active', true)
            ->with(['category', 'media'])
            ->paginate(12);

        return view('livewire.home', [
            'products' => $products,
            'categories' => $categories,
        ])->layout('layouts.app');
    }
}
