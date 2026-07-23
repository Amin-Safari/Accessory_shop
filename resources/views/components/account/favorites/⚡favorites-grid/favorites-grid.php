<?php

use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public $perPage = 12;
    public $sortBy = 'newest';
    public $showDeleteModal = false;
    public $productToDelete = null;

    protected $queryString = ['sortBy'];

    public function sortBy($type)
    {
        $this->sortBy = $type;
        $this->resetPage();
    }

    public function confirmDelete($productId)
    {
        $this->productToDelete = $productId;
        $this->showDeleteModal = true;
    }

    public function removeFromFavorites()
    {
        if ($this->productToDelete) {
            auth()->user()->favorites()
                ->where('product_id', $this->productToDelete)
                ->delete();

            $this->showDeleteModal = false;
            $this->productToDelete = null;

            session()->flash('favorite_removed', 'محصول با موفقیت از علاقه‌مندی‌ها حذف شد.');

            $this->dispatch('favorite-removed');
        }
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->productToDelete = null;
    }

    public function removeAllFavorites()
    {
        auth()->user()->favorites()->delete();

        session()->flash('all_favorites_removed', 'همه محصولات از علاقه‌مندی‌ها حذف شدند.');

        $this->dispatch('all-favorites-removed');
    }

    public function render()
    {
        $favorites = auth()->user()->favorites()
            ->with(['product' => function ($query) {
                $query->select('id', 'category_id', 'name', 'images', 'price', 'discount', 'total', 'slug', 'is_active')
                    ->with('category:id,name');
            }])
            ->when($this->sortBy === 'newest', function ($query) {
                return $query->latest();
            })
            ->when($this->sortBy === 'oldest', function ($query) {
                return $query->oldest();
            })
            ->when($this->sortBy === 'price_asc', function ($query) {
                return $query->join('products', 'user_favorites.product_id', '=', 'products.id')
                    ->orderBy('products.price', 'asc')
                    ->select('user_favorites.*');
            })
            ->when($this->sortBy === 'price_desc', function ($query) {
                return $query->join('products', 'user_favorites.product_id', '=', 'products.id')
                    ->orderBy('products.price', 'desc')
                    ->select('user_favorites.*');
            })
            ->paginate($this->perPage);

        return view('components.account.favorites.⚡favorites-grid.favorites-grid', compact('favorites'), [
            'favorites' => $favorites
        ]);
    }
};
