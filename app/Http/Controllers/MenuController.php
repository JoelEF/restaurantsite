<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MenuItem;

class MenuController extends Controller
{
    public function index()
    {
        $categories = Category::with('activeMenuItems')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('menu', compact('categories'));
    }

    public function show(MenuItem $menuItem)
    {
        $menuItem->load('category');
        $related = MenuItem::where('category_id', $menuItem->category_id)
            ->where('id', '!=', $menuItem->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('menu-item', compact('menuItem', 'related'));
    }
}
