<?php

namespace App\Livewire;

use App\Models\MenuItem;
use Livewire\Component;

class ShoppingCart extends Component
{
    public array $cart = [];
    public bool $isOpen = false;

    protected $listeners = ['add-item' => 'addItem'];

    public function mount(): void
    {
        $this->cart = session()->get('cart', []);
    }

    public function addItem(int $menuItemId): void
    {
        $menuItem = MenuItem::find($menuItemId);
        if (!$menuItem) {
            return;
        }

        if (isset($this->cart[$menuItemId])) {
            $this->cart[$menuItemId]['quantity']++;
        } else {
            $this->cart[$menuItemId] = [
                'id' => $menuItem->id,
                'name' => $menuItem->name,
                'price' => (float) $menuItem->price,
                'quantity' => 1,
            ];
        }

        session()->put('cart', $this->cart);
        $this->isOpen = true;
        $this->dispatch('cart-updated', count: $this->totalItems());
    }

    public function removeItem(int $menuItemId): void
    {
        unset($this->cart[$menuItemId]);
        session()->put('cart', $this->cart);
        $this->dispatch('cart-updated', count: $this->totalItems());
    }

    public function decreaseQuantity(int $menuItemId): void
    {
        if (isset($this->cart[$menuItemId])) {
            if ($this->cart[$menuItemId]['quantity'] <= 1) {
                $this->removeItem($menuItemId);
                return;
            }
            $this->cart[$menuItemId]['quantity']--;
            session()->put('cart', $this->cart);
            $this->dispatch('cart-updated', count: $this->totalItems());
        }
    }

    public function clearCart(): void
    {
        $this->cart = [];
        session()->forget('cart');
        $this->dispatch('cart-updated', count: 0);
    }

    public function toggleCart(): void
    {
        $this->isOpen = !$this->isOpen;
    }

    public function totalItems(): int
    {
        return array_sum(array_column($this->cart, 'quantity'));
    }

    public function subtotal(): float
    {
        return array_sum(array_map(
            fn($item) => $item['price'] * $item['quantity'],
            $this->cart
        ));
    }

    public function render()
    {
        return view('livewire.shopping-cart');
    }
}
