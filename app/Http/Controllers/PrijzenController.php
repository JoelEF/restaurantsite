<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class PrijzenController extends Controller
{
    public function index()
    {
        $categories = Category::with('menuItems')->orderBy('sort_order')->get();
        return view('admin.prijzen', compact('categories'));
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'mode'       => 'required|in:all,category,individual',
            'percentage' => 'nullable|numeric|min:-50|max:100',
            'category_id' => 'nullable|exists:categories,id',
            'prices'     => 'nullable|array',
            'prices.*'   => 'nullable|numeric|min:0',
        ]);

        if ($request->mode === 'individual' && $request->prices) {
            foreach ($request->prices as $id => $price) {
                if ($price !== null && $price !== '') {
                    MenuItem::where('id', $id)->update(['price' => round((float) $price, 2)]);
                }
            }
            return back()->with('success', 'Prijzen bijgewerkt.');
        }

        if ($request->percentage === null) {
            return back()->withErrors(['percentage' => 'Vul een percentage in.']);
        }

        $factor = 1 + ($request->percentage / 100);

        $query = MenuItem::query();
        if ($request->mode === 'category' && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        foreach ($query->get() as $item) {
            $item->update(['price' => round($item->price * $factor, 2)]);
        }

        $scope = $request->mode === 'all'
            ? 'alle items'
            : 'categorie ' . Category::find($request->category_id)?->name;

        return back()->with('success', "Prijzen van {$scope} bijgewerkt met {$request->percentage}%.");
    }
}
