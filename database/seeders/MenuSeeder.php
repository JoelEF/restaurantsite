<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Döner & Kebab', 'slug' => 'doner-kebab', 'icon' => '🥙', 'sort_order' => 1],
            ['name' => 'Wraps & Dürüm', 'slug' => 'wraps-durum', 'icon' => '🌯', 'sort_order' => 2],
            ['name' => 'Pita & Brood', 'slug' => 'pita-brood', 'icon' => '🫓', 'sort_order' => 3],
            ['name' => 'Lahmacun', 'slug' => 'lahmacun', 'icon' => '🫔', 'sort_order' => 4],
            ['name' => 'Bijgerechten', 'slug' => 'bijgerechten', 'icon' => '🍟', 'sort_order' => 5],
            ['name' => 'Sauzen', 'slug' => 'sauzen', 'icon' => '🫙', 'sort_order' => 6],
            ['name' => 'Drankjes', 'slug' => 'drankjes', 'icon' => '🥤', 'sort_order' => 7],
        ];

        foreach ($categories as $catData) {
            $category = Category::create($catData);

            $items = $this->getItemsForCategory($catData['slug']);
            foreach ($items as $item) {
                $item['category_id'] = $category->id;
                $item['slug'] = Str::slug($item['name']) . '-' . Str::random(4);
                MenuItem::create($item);
            }
        }
    }

    private function getItemsForCategory(string $slug): array
    {
        return match ($slug) {
            'doner-kebab' => [
                ['name' => 'Kip Döner Bord', 'description' => 'Sappige kip döner van de spit, geserveerd met verse sla, tomaat, ui en huissaus.', 'price' => 12.50, 'is_popular' => true, 'sort_order' => 1],
                ['name' => 'Vlees Döner Bord', 'description' => 'Traditioneel lams- en kalfsvlees döner, geserveerd met frisse salade en yoghurtsaus.', 'price' => 13.50, 'is_popular' => true, 'sort_order' => 2],
                ['name' => 'Gemengd Döner Bord', 'description' => 'Combinatie van kip en lams döner, met sla, tomaat, komkommer en knoflooksaus.', 'price' => 14.50, 'sort_order' => 3],
                ['name' => 'Adana Kebab', 'description' => 'Gekruide gehakt spiesjes van lam, gegrild op houtskool, met bulgur en salade.', 'price' => 15.00, 'is_spicy' => true, 'sort_order' => 4],
                ['name' => 'Shish Kebab', 'description' => 'Malse stukken lam op spiesjes, gegrild met paprika en ui.', 'price' => 15.50, 'sort_order' => 5],
                ['name' => 'Falafel Bord', 'description' => 'Krokante falafel balletjes van kikkererwten, met taboulé, hummus en pitabrood.', 'price' => 11.00, 'is_vegetarian' => true, 'sort_order' => 6],
            ],
            'wraps-durum' => [
                ['name' => 'Kip Dürüm', 'description' => 'Dunne dürüm gevuld met kip döner, sla, tomaat, ui en knoflooksaus.', 'price' => 8.50, 'is_popular' => true, 'sort_order' => 1],
                ['name' => 'Vlees Dürüm', 'description' => 'Sappige dürüm met lams döner, verse groenten en pikante harissasaus.', 'price' => 9.00, 'sort_order' => 2],
                ['name' => 'Gemengd Dürüm', 'description' => 'Kip én vlees döner in een heerlijke dürüm, met salade en dubbele saus.', 'price' => 9.50, 'sort_order' => 3],
                ['name' => 'Veggie Wrap', 'description' => 'Falafel, hummus, gegrilde groenten en tzatziki in een verse dürüm.', 'price' => 8.00, 'is_vegetarian' => true, 'sort_order' => 4],
            ],
            'pita-brood' => [
                ['name' => 'Kip Pita', 'description' => 'Knapperige pita gevuld met kip döner, sla, tomaat en knoflooksaus.', 'price' => 7.50, 'is_popular' => true, 'sort_order' => 1],
                ['name' => 'Vlees Pita', 'description' => 'Klassieke pita met lams döner, verse salade en yoghurtsaus.', 'price' => 8.00, 'sort_order' => 2],
                ['name' => 'Dubbele Pita', 'description' => 'Extra groot gevulde pita met dubbele portie döner en alle topping.', 'price' => 10.50, 'sort_order' => 3],
                ['name' => 'Falafel Pita', 'description' => 'Krokante falafel in pita met hummus, taboulé en tahini.', 'price' => 7.00, 'is_vegetarian' => true, 'sort_order' => 4],
            ],
            'lahmacun' => [
                ['name' => 'Lahmacun (1 stuk)', 'description' => 'Dun Turks brood belegd met gekruid gehakt, ui en tomaat, gebakken in een steenoven.', 'price' => 5.00, 'sort_order' => 1],
                ['name' => 'Lahmacun (2 stuks)', 'description' => 'Twee krokante lahmacun, perfect om te rollen met verse sla en citroen.', 'price' => 9.00, 'is_popular' => true, 'sort_order' => 2],
                ['name' => 'Lahmacun Wrap', 'description' => 'Lahmacun gerold met sla, tomaat, ui, peterselie en citroensap.', 'price' => 7.00, 'sort_order' => 3],
            ],
            'bijgerechten' => [
                ['name' => 'Friet', 'description' => 'Krokante Belgische friet, groot of klein, met saus naar keuze.', 'price' => 3.50, 'sort_order' => 1],
                ['name' => 'Friet met Saus', 'description' => 'Krokante friet met een saus naar keuze: mayo, ketchup of knoflook.', 'price' => 4.50, 'is_popular' => true, 'sort_order' => 2],
                ['name' => 'Kipnuggets (6 stuks)', 'description' => 'Knapperige kipnuggets, geserveerd met dipsaus.', 'price' => 5.50, 'sort_order' => 3],
                ['name' => 'Hummus', 'description' => 'Romige hummus van kikkererwten met olijfolie en paprikapoeder, met pitabrood.', 'price' => 4.00, 'is_vegetarian' => true, 'sort_order' => 4],
                ['name' => 'Taboulé Salade', 'description' => 'Verse taboulé met peterselie, tomaat, bulgur, citroen en olijfolie.', 'price' => 4.50, 'is_vegetarian' => true, 'sort_order' => 5],
                ['name' => 'Grillgroenten', 'description' => 'Gegrilde courgette, paprika, aubergine en ui met kruidenmarinage.', 'price' => 4.00, 'is_vegetarian' => true, 'sort_order' => 6],
            ],
            'sauzen' => [
                ['name' => 'Knoflooksaus', 'description' => 'Romige knoflooksaus, huisgemaakt.', 'price' => 0.75, 'sort_order' => 1],
                ['name' => 'Tzatziki', 'description' => 'Griekse yoghurtsaus met komkommer, knoflook en dille.', 'price' => 0.75, 'sort_order' => 2],
                ['name' => 'Harissa', 'description' => 'Pikante rode pepersaus — voor de liefhebbers!', 'price' => 0.75, 'is_spicy' => true, 'sort_order' => 3],
                ['name' => 'Tahini', 'description' => 'Sesampasta saus, licht geroosterd met citroen.', 'price' => 0.75, 'sort_order' => 4],
            ],
            'drankjes' => [
                ['name' => 'Cola (0.33L)', 'description' => 'Gekoeld blikje cola.', 'price' => 2.50, 'sort_order' => 1],
                ['name' => 'Fanta (0.33L)', 'description' => 'Gekoeld blikje fanta sinaasappel.', 'price' => 2.50, 'sort_order' => 2],
                ['name' => 'Sprite (0.33L)', 'description' => 'Gekoeld blikje sprite.', 'price' => 2.50, 'sort_order' => 3],
                ['name' => 'Ayran', 'description' => 'Traditionele Turkse yoghurtdrank, licht gezouten en verfrissend.', 'price' => 2.75, 'is_popular' => true, 'sort_order' => 4],
                ['name' => 'Turkse Thee', 'description' => 'Sterke Turkse zwarte thee geserveerd in een tulpenglas.', 'price' => 2.00, 'sort_order' => 5],
                ['name' => 'Water (0.5L)', 'description' => 'Still bronwater.', 'price' => 1.50, 'sort_order' => 6],
            ],
            default => [],
        };
    }
}
