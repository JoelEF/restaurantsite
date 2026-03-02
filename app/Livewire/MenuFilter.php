<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\MenuItem;
use Livewire\Component;

class MenuFilter extends Component
{
    public ?int $activeCategory = null;
    public string $search = '';
    public string $filter = 'all';

    public function render()
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();

        $query = MenuItem::with('category')->where('is_active', true);

        if ($this->activeCategory) {
            $query->where('category_id', $this->activeCategory);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filter === 'popular') {
            $query->where('is_popular', true);
        } elseif ($this->filter === 'vegetarian') {
            $query->where('is_vegetarian', true);
        } elseif ($this->filter === 'spicy') {
            $query->where('is_spicy', true);
        }

        $items = $query->orderBy('sort_order')->get();

        return view('livewire.menu-filter', compact('categories', 'items'));
    }
}
