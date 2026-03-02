<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MenuItem;

class HomeController extends Controller
{
    public function index()
    {
        $popularItems = MenuItem::with('category')
            ->where('is_popular', true)
            ->where('is_active', true)
            ->take(6)
            ->get();

        return view('home', compact('popularItems'));
    }
}
