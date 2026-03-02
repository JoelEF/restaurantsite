<?php

namespace App\Filament\Pages;

use App\Models\Category;
use App\Models\MenuItem;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class PrijzenBeheer extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-currency-euro';
    protected static ?string $navigationLabel = 'Prijzen beheren';
    protected static ?string $title           = 'Prijzen beheren';
    protected static ?string $slug            = 'prijzen';
    protected static ?string $navigationGroup = 'Menu Beheer';
    protected static ?int    $navigationSort  = 10;

    protected static string $view = 'filament.pages.prijzen-beheer';

    // Bulk form
    public string $mode       = 'all';
    public ?int   $categoryId = null;
    public ?float $percentage = null;

    // Individual prices  [id => price]
    public array $prices = [];

    public function mount(): void
    {
        $this->prices = MenuItem::pluck('price', 'id')
            ->map(fn($p) => number_format((float) $p, 2, '.', ''))
            ->toArray();
    }

    public function getCategories()
    {
        return Category::with('menuItems')->orderBy('sort_order')->get();
    }

    public function applyPercentage(): void
    {
        if ($this->percentage === null) {
            Notification::make()->title('Vul een percentage in.')->danger()->send();
            return;
        }

        $factor = 1 + ($this->percentage / 100);
        $query  = MenuItem::query();

        if ($this->mode === 'category' && $this->categoryId) {
            $query->where('category_id', $this->categoryId);
        }

        DB::transaction(function () use ($query, $factor) {
            foreach ($query->get() as $item) {
                $item->update(['price' => round($item->price * $factor, 2)]);
            }
        });

        // Refresh local prices array
        $this->prices = MenuItem::pluck('price', 'id')
            ->map(fn($p) => number_format((float) $p, 2, '.', ''))
            ->toArray();

        $scope = $this->mode === 'all'
            ? 'alle items'
            : 'categorie ' . Category::find($this->categoryId)?->name;

        Notification::make()
            ->title("Prijzen van {$scope} bijgewerkt met {$this->percentage}%")
            ->success()
            ->send();

        $this->percentage = null;
    }

    public function savePrices(): void
    {
        DB::transaction(function () {
            foreach ($this->prices as $id => $price) {
                if ($price !== null && $price !== '') {
                    MenuItem::where('id', $id)->update(['price' => round((float) $price, 2)]);
                }
            }
        });

        Notification::make()->title('Prijzen opgeslagen.')->success()->send();
    }
}
