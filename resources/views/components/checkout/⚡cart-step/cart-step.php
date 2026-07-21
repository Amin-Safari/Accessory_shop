<?php

use App\Models\Product;
use App\Services\InventoryReservationService;
use Livewire\Component;

new class extends Component
{
    public array $cartItems = [];
    public array $validationErrors = [];
    public bool $isLoading = false;

    protected InventoryReservationService $inventoryService;

    public function boot(InventoryReservationService $inventoryService): void
    {
        $this->inventoryService = $inventoryService;
    }

    public function mount(): void
    {
        $this->loadCart();
    }

    public function loadCart(): void
    {
        $cart = session('cart', []);

        // تبدیل آرایه سبد خرید با اطلاعات کامل محصول
        $this->cartItems = collect($cart)->map(function ($item) {
            $product = Product::find($item['id']);
            return [
                'id' => $item['id'],
                'product_id' => $item['id'],
                'name' => $product->name ?? $item['title'],
                'price' => $product->price ?? $item['price'],
                'quantity' => $item['qty'],
                'discount' => $item['discount'] ?? 0,
                'image' => $product->image_url ?? null,
                'is_available' => $product && $product->is_active && $product->total > 0,
                'stock' => $product->total ?? 0,
            ];
        })->toArray();
    }

    public function updateQuantity($itemId, $newQuantity): void
    {
        $cart = session('cart', []);

        if (isset($cart[$itemId])) {
            if ($newQuantity <= 0) {
                unset($cart[$itemId]);
            } else {
                $cart[$itemId]['qty'] = $newQuantity;
            }

            session()->put('cart', $cart);
            $this->loadCart();
            $this->dispatch('cart-updated');
        }
    }

    public function removeItem($itemId): void
    {
        $cart = session('cart', []);
        unset($cart[$itemId]);
        session()->put('cart', $cart);
        $this->loadCart();
        $this->dispatch('cart-updated');
    }

    public function validateCart(): bool
    {
        if (!Auth::check()) {
            $this->dispatch('open-auth');
            return false;
        }

        if (empty($this->cartItems)) {
            $this->validationErrors[] = 'سبد خرید شما خالی است';
            return false;
        }

        // بررسی موجودی و فعال بودن محصولات
        $inventoryValidation = $this->inventoryService->validateCartInventory(
            collect($this->cartItems)->map(function ($item) {
                return [
                    'product_id' => $item['product_id'],
                    'product_name' => $item['name'],
                    'quantity' => $item['quantity'],
                ];
            })->toArray()
        );

        if (!$inventoryValidation['valid']) {
            $this->validationErrors = $inventoryValidation['errors'];
            return false;
        }

        return true;
    }

    public function proceedToNextStep(): void
    {
        if ($this->validateCart()) {
            $this->dispatch('nextStep');
        }
    }

    public function getSubtotalProperty(): int
    {
        return collect($this->cartItems)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });
    }

    public function getDiscountAmountProperty(): int
    {
        return collect($this->cartItems)->sum(function ($item) {
            return ($item['price'] * $item['quantity'] * $item['discount']) / 100;
        });
    }

    public function getTotalItemsProperty(): int
    {
        return collect($this->cartItems)->sum('quantity');
    }
};
