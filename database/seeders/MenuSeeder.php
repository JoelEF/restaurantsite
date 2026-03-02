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
        Category::truncate();
        MenuItem::truncate();

        $categories = [
            ['name' => 'Salades',           'slug' => 'salades',           'icon' => '🥗', 'sort_order' => 1],
            ['name' => 'Broodjes',           'slug' => 'broodjes',          'icon' => '🥙', 'sort_order' => 2],
            ['name' => 'Kapsalon',           'slug' => 'kapsalon',          'icon' => '🍟', 'sort_order' => 3],
            ['name' => 'Extra',              'slug' => 'extra',             'icon' => '🫙', 'sort_order' => 4],
            ['name' => "Kindermenu's",       'slug' => 'kindermenus',       'icon' => '🧒', 'sort_order' => 5],
            ['name' => 'Vegetarische pizza', 'slug' => 'vegetarische-pizza','icon' => '🌿', 'sort_order' => 6],
            ['name' => 'Vispizza',           'slug' => 'vispizza',          'icon' => '🐟', 'sort_order' => 7],
            ['name' => 'Vlees pizza',        'slug' => 'vlees-pizza',       'icon' => '🍕', 'sort_order' => 8],
            ['name' => 'Calzone',            'slug' => 'calzone',           'icon' => '🫔', 'sort_order' => 9],
            ['name' => 'Pasta',              'slug' => 'pasta',             'icon' => '🍝', 'sort_order' => 10],
            ['name' => 'Vleesgerechten',     'slug' => 'vleesgerechten',    'icon' => '🥩', 'sort_order' => 11],
            ['name' => 'Spareribs',          'slug' => 'spareribs',         'icon' => '🍖', 'sort_order' => 12],
            ['name' => 'Dranken',            'slug' => 'dranken',           'icon' => '🥤', 'sort_order' => 13],
        ];

        foreach ($categories as $catData) {
            $category = Category::create($catData);
            foreach ($this->getItemsForCategory($catData['slug']) as $item) {
                $item['category_id'] = $category->id;
                $item['slug'] = Str::slug($item['name']) . '-' . Str::random(4);
                MenuItem::create($item);
            }
        }
    }

    private function getItemsForCategory(string $slug): array
    {
        return match ($slug) {
            'salades' => [
                ['name' => 'Insalata caprese',    'description' => 'Gemengde salade met tomaat, mozzarella en basilicum', 'price' => 7.95, 'sort_order' => 1, 'is_vegetarian' => true],
                ['name' => 'Insalata Otterlo',    'description' => 'Boerensalade met tomaat, komkommer, ui en feta',      'price' => 8.95, 'sort_order' => 2, 'is_vegetarian' => true],
                ['name' => 'Insalata di tonno',   'description' => 'Gemengde salade met tonijn en uien',                  'price' => 8.95, 'sort_order' => 3],
                ['name' => 'Insalata gorgonzola', 'description' => 'Gemengde salade met gorgonzola kaas',                 'price' => 7.95, 'sort_order' => 4, 'is_vegetarian' => true],
                ['name' => 'Insalata mista',      'description' => 'Gemengde salade',                                     'price' => 6.95, 'sort_order' => 5, 'is_vegetarian' => true],
            ],
            'broodjes' => [
                ['name' => 'Shoarma',              'description' => '',                                     'price' => 6.95, 'sort_order' => 1],
                ['name' => 'Broodje Döner',        'description' => '',                                     'price' => 6.45, 'sort_order' => 2],
                ['name' => 'Broodje Kebab',        'description' => '',                                     'price' => 6.95, 'sort_order' => 3],
                ['name' => 'Döner special',        'description' => 'Champignons, paprika, ui',             'price' => 7.45, 'sort_order' => 4],
                ['name' => 'Kipfilet broodje',     'description' => 'Champignons, paprika, ui',             'price' => 7.45, 'sort_order' => 5],
                ['name' => 'Turkse pizza speciaal','description' => 'Döner, sla, tomaat, komkommer',        'price' => 6.95, 'sort_order' => 6],
                ['name' => 'Shoarma special',      'description' => 'Champignons, paprika, ui',             'price' => 7.45, 'sort_order' => 7],
                ['name' => 'Turkse Pizza',         'description' => '',                                     'price' => 3.95, 'sort_order' => 8],
                ['name' => 'Stokbrood',            'description' => 'Met kruidenboter',                    'price' => 3.95, 'sort_order' => 9,  'is_vegetarian' => true],
                ['name' => 'Hamburger broodje',    'description' => 'Sla, komkommer en tomaat',            'price' => 5.95, 'sort_order' => 10],
                ['name' => 'Broodje gezond',       'description' => 'Turks brood, sla, komkommer en kaas', 'price' => 5.95, 'sort_order' => 11, 'is_vegetarian' => true],
                ['name' => 'Pita kaas',            'description' => '',                                     'price' => 2.00, 'sort_order' => 12, 'is_vegetarian' => true],
                ['name' => 'Pita ham/kaas',        'description' => '',                                     'price' => 2.45, 'sort_order' => 13],
                ['name' => 'Pita hawaï',           'description' => 'Kaas, ananas en ham',                 'price' => 2.95, 'sort_order' => 14],
            ],
            'kapsalon' => [
                ['name' => 'Kapsalon chicken',        'description' => '', 'price' => 9.95, 'sort_order' => 1, 'is_popular' => true],
                ['name' => 'Kapsalon Tagin speciaal', 'description' => '', 'price' => 9.95, 'sort_order' => 2],
                ['name' => 'Kapsalon döner',          'description' => '', 'price' => 9.95, 'sort_order' => 3],
                ['name' => 'Kapsalon shoarma',        'description' => '', 'price' => 9.95, 'sort_order' => 4],
            ],
            'extra' => [
                ['name' => 'Kruidenboter',              'description' => '', 'price' => 1.50, 'sort_order' => 1, 'is_vegetarian' => true],
                ['name' => 'Knoflooksaus',              'description' => '', 'price' => 0.75, 'sort_order' => 2, 'is_vegetarian' => true],
                ['name' => 'Sambalsaus',                'description' => '', 'price' => 0.75, 'sort_order' => 3, 'is_spicy' => true],
                ['name' => 'Tomaten-ui saus',           'description' => '', 'price' => 0.75, 'sort_order' => 4, 'is_vegetarian' => true],
                ['name' => 'Cocktailsaus',              'description' => '', 'price' => 0.75, 'sort_order' => 5, 'is_vegetarian' => true],
                ['name' => 'Pita brood',                'description' => '', 'price' => 1.00, 'sort_order' => 6, 'is_vegetarian' => true],
                ['name' => 'Turks brood',               'description' => '', 'price' => 1.00, 'sort_order' => 7, 'is_vegetarian' => true],
                ['name' => 'Portie patat',              'description' => '', 'price' => 2.95, 'sort_order' => 8, 'is_vegetarian' => true],
                ['name' => 'Portie gebakken aardappels','description' => '', 'price' => 2.45, 'sort_order' => 9, 'is_vegetarian' => true],
            ],
            'kindermenus' => [
                ['name' => 'Chicken nuggets',         'description' => '5 kipnuggets met patat',  'price' => 8.95, 'sort_order' => 1],
                ['name' => 'Chicken wings',           'description' => '5 stukjes kip met patat', 'price' => 9.95, 'sort_order' => 2],
                ['name' => 'Frikandel',               'description' => 'Met patat',               'price' => 6.95, 'sort_order' => 3],
                ['name' => 'Pizza bambino margherita','description' => 'Tomaat en kaas',           'price' => 6.95, 'sort_order' => 4, 'is_vegetarian' => true],
            ],
            'vegetarische-pizza' => [
                ['name' => 'Vegetariana',        'description' => 'Tomaten, kaas, paprika, champignons, uien, artisjokken en olijven', 'price' => 11.95, 'sort_order' => 1, 'is_vegetarian' => true],
                ['name' => 'Pizza al la Juanita','description' => 'Tomaten, kaas, diverse groenten en gorgonzola',                    'price' => 11.95, 'sort_order' => 2, 'is_vegetarian' => true],
                ['name' => 'Pizza mozzarella',   'description' => 'Tomaten, kaas en mozzarella',                                      'price' => 11.95, 'sort_order' => 3, 'is_vegetarian' => true],
                ['name' => 'Quattro formaggi',   'description' => 'Tomaten en vier soorten kaas',                                     'price' => 12.95, 'sort_order' => 4, 'is_vegetarian' => true],
                ['name' => 'Pizza gorgonzola',   'description' => 'Tomaten, kaas en gorgonzola',                                      'price' => 11.95, 'sort_order' => 5, 'is_vegetarian' => true],
                ['name' => 'Pizza ananas',       'description' => 'Tomaten, kaas, ananas',                                            'price' => 8.95,  'sort_order' => 6, 'is_vegetarian' => true],
                ['name' => 'Tutti frutti',       'description' => 'Tomaten, kaas, fruit',                                             'price' => 11.95, 'sort_order' => 7, 'is_vegetarian' => true],
                ['name' => 'Margherita',         'description' => 'Tomaten en kaas',                                                  'price' => 7.95,  'sort_order' => 8, 'is_vegetarian' => true, 'is_popular' => true],
                ['name' => 'Funghi',             'description' => 'Tomaten, kaas en champignons',                                     'price' => 8.95,  'sort_order' => 9, 'is_vegetarian' => true],
            ],
            'vispizza' => [
                ['name' => 'Pizza scampi',   'description' => 'Tomaten, kaas, garnalen en uien',                 'price' => 11.95, 'sort_order' => 1],
                ['name' => 'Frutti di mare', 'description' => 'Tomaten, kaas, zeevruchten en ansjovis',          'price' => 13.95, 'sort_order' => 2],
                ['name' => 'Siciliana',      'description' => 'Tomaten, kaas, kappertjes, ansjovis en olijven',  'price' => 12.95, 'sort_order' => 3],
                ['name' => 'Tonno',          'description' => 'Tomaten, kaas, tonijn en ui',                     'price' => 11.95, 'sort_order' => 4],
                ['name' => 'Napoletana',     'description' => 'Tomaten, kaas en ansjovis',                       'price' => 9.95,  'sort_order' => 5],
            ],
            'vlees-pizza' => [
                ['name' => 'Pizza shawarma',         'description' => 'Tomaten, kaas en shoarma',                                    'price' => 11.95, 'sort_order' => 1],
                ['name' => 'Pizza döner',            'description' => 'Tomaten, kaas en döner',                                      'price' => 11.95, 'sort_order' => 2],
                ['name' => 'Pizza pollo speciale',   'description' => 'Tomaten, kaas, champignons, paprika, ui en kipfilet',         'price' => 13.95, 'sort_order' => 3],
                ['name' => 'Pizza speciale shoarma', 'description' => 'Tomaten, kaas, champignons, paprika, ui en shoarma',         'price' => 13.95, 'sort_order' => 4],
                ['name' => 'Pizza speciale döner',   'description' => 'Tomaten, kaas, champignons, paprika, ui en döner',           'price' => 13.95, 'sort_order' => 5],
                ['name' => 'Pizza luna',             'description' => 'Tomaten, 4 soorten vlees, kaas, champignons, paprika en ui', 'price' => 14.95, 'sort_order' => 6, 'is_popular' => true],
                ['name' => 'Pizza prosciutto funghi','description' => 'Tomaten, kaas, ham en champignons',                          'price' => 10.95, 'sort_order' => 7],
                ['name' => 'Pizza diavola',          'description' => 'Tomaten, kaas, salami en Spaanse pepers',                    'price' => 10.95, 'sort_order' => 8, 'is_spicy' => true],
                ['name' => 'Pizza quattro stagioni', 'description' => 'Tomaten, kaas, ham, salami, champignons en paprika',         'price' => 11.95, 'sort_order' => 9],
                ['name' => 'Pizza prosciutto',       'description' => 'Tomaten, kaas en ham',                                       'price' => 9.95,  'sort_order' => 10],
                ['name' => 'Pizza salami',           'description' => 'Tomaten, kaas en salami',                                    'price' => 9.95,  'sort_order' => 11],
                ['name' => 'Pizza Hawai',            'description' => 'Tomaten, kaas, ham en ananas',                               'price' => 10.95, 'sort_order' => 12],
            ],
            'calzone' => [
                ['name' => 'Calzone shoarma',    'description' => 'Dubbelgevouwen pizza met tomaten, kaas, paprika, ui, champignons en shoarma',  'price' => 13.95, 'sort_order' => 1],
                ['name' => 'Calzone pollo',      'description' => 'Tomaten, kaas, paprika, ui, champignon en kipfilet',                          'price' => 13.95, 'sort_order' => 2],
                ['name' => 'Calzone döner',      'description' => 'Dubbelgevouwen pizza met tomaten, kaas, paprika, ui, champignons en döner',   'price' => 13.95, 'sort_order' => 3],
                ['name' => 'Calzone vegetariana','description' => 'Tomaten, kaas, paprika, champignons, ui, artisjokken en olijven',             'price' => 13.95, 'sort_order' => 4, 'is_vegetarian' => true],
                ['name' => 'Calzone classico',   'description' => 'Dubbelgevouwen pizza met tomaten, kaas, ham, salami en groente',              'price' => 11.95, 'sort_order' => 5],
            ],
            'pasta' => [
                ['name' => 'Vegetaria',            'description' => 'Met diverse groenten',                              'price' => 10.95, 'sort_order' => 1,  'is_vegetarian' => true],
                ['name' => 'Pasta a la Juanita',   'description' => 'Met room, champignons, knoflookolie en gorgonzola', 'price' => 10.95, 'sort_order' => 2,  'is_vegetarian' => true],
                ['name' => 'Al pollo',             'description' => 'Kipfilet, groenten en roomsaus',                   'price' => 11.95, 'sort_order' => 3],
                ['name' => 'Pasta frutti di mare', 'description' => 'Tomaten, kaas en zeevruchten',                     'price' => 11.95, 'sort_order' => 4],
                ['name' => 'Tortellini',           'description' => 'Met knoflook, spinazie en champignons in roomsaus', 'price' => 11.95, 'sort_order' => 5,  'is_vegetarian' => true],
                ['name' => 'A la casa',            'description' => 'Met gebakken ei, room en ham',                     'price' => 10.95, 'sort_order' => 6],
                ['name' => 'Al tonno',             'description' => 'Met tonijn, ui en tomatensaus',                    'price' => 10.95, 'sort_order' => 7],
                ['name' => 'Al formaggio',         'description' => 'Met 4 soorten kaas, knoflookolie en room',         'price' => 11.95, 'sort_order' => 8,  'is_vegetarian' => true],
                ['name' => 'Al napoletana',        'description' => 'Tomatensaus met verschillende kruiden',             'price' => 7.95,  'sort_order' => 9,  'is_vegetarian' => true],
                ['name' => 'Al bolognese',         'description' => 'Met rundergehaktsaus',                              'price' => 10.95, 'sort_order' => 10],
            ],
            'vleesgerechten' => [
                ['name' => 'Kipfilet',           'description' => 'Kipfilet met gebakken champignons, paprika en ui',                               'price' => 13.95, 'sort_order' => 1],
                ['name' => 'Hamburger',          'description' => 'Hamburger met champignons, paprika en ui',                                       'price' => 13.95, 'sort_order' => 2],
                ['name' => 'Schnitzel',          'description' => 'Met champignons in roomsaus',                                                    'price' => 13.95, 'sort_order' => 3],
                ['name' => 'Mixed grill',        'description' => 'Met shoarma, kip, doner en uien',                                               'price' => 13.95, 'sort_order' => 4, 'is_popular' => true],
                ['name' => 'Mixed grill special','description' => 'Met shoarma, kip, doner, gebakken champignons, paprika en uien',                 'price' => 16.95, 'sort_order' => 5],
                ['name' => 'Luna schotel',       'description' => 'Met shoarma, doner, kip, kebab en spareribs',                                   'price' => 18.95, 'sort_order' => 6, 'is_popular' => true],
                ['name' => 'Shoarma special',    'description' => 'Met champignons, paprika en ui',                                                 'price' => 13.95, 'sort_order' => 7],
                ['name' => 'Döner special',      'description' => 'Met champignons, paprika en ui',                                                 'price' => 13.95, 'sort_order' => 8],
                ['name' => 'Shoarma Hawaï',      'description' => 'Traditioneel gekruide vleesreepjes met ananas en gesmolten kaas',                'price' => 13.95, 'sort_order' => 9],
                ['name' => 'Kebab',              'description' => 'Pittig gekruide rundergehaktstaaf',                                              'price' => 12.95, 'sort_order' => 10, 'is_spicy' => true],
                ['name' => 'Shoarma',            'description' => 'Traditioneel gekruide vleesreepjes',                                             'price' => 12.95, 'sort_order' => 11],
                ['name' => 'Döner',              'description' => 'Traditioneel gekruid kalfsvlees',                                                'price' => 12.95, 'sort_order' => 12],
            ],
            'spareribs' => [
                ['name' => 'Spareribs Piri-Piri','description' => 'Met pittige Piri-Piri smaak', 'price' => 16.95, 'sort_order' => 1, 'is_spicy' => true],
                ['name' => 'Spareribs BBQ',      'description' => 'Met BBQ smaak',              'price' => 16.95, 'sort_order' => 2],
                ['name' => 'Spareribs Ananas',   'description' => 'Met ananas',                 'price' => 16.95, 'sort_order' => 3],
                ['name' => 'Spareribs naturel',  'description' => '',                           'price' => 16.95, 'sort_order' => 4],
            ],
            'dranken' => [
                ['name' => 'Fles bier Hertog Jan',                       'description' => '5% vol',                                                                  'price' => 3.50,  'sort_order' => 1],
                ['name' => 'Fles wijn wit',                              'description' => '13% vol',                                                                 'price' => 12.95, 'sort_order' => 2],
                ['name' => 'Fles wijn rood',                             'description' => '13% vol',                                                                 'price' => 12.95, 'sort_order' => 3],
                ['name' => 'Cola light',                                 'description' => '',                                                                         'price' => 3.00,  'sort_order' => 4],
                ['name' => 'Sinas',                                      'description' => '',                                                                         'price' => 3.00,  'sort_order' => 5],
                ['name' => 'Coca-Cola Regular 330ml',                    'description' => '0,33l',                                                                   'price' => 3.50,  'sort_order' => 6, 'is_popular' => true],
                ['name' => 'Coca-Cola Zero 330ml',                       'description' => '0,33l',                                                                   'price' => 3.50,  'sort_order' => 7],
                ['name' => 'Sprite 330ml',                               'description' => '0,33l',                                                                   'price' => 3.50,  'sort_order' => 8],
                ['name' => 'Fanta Orange 330ml',                         'description' => '0,33l',                                                                   'price' => 3.50,  'sort_order' => 9],
                ['name' => 'Fuze Tea Sparkling Green 330ml',             'description' => '0,33l',                                                                   'price' => 3.50,  'sort_order' => 10],
                ['name' => 'Chaudfontaine 500ml',                        'description' => '0,5l',                                                                    'price' => 4.00,  'sort_order' => 11],
                ['name' => 'Cola',                                        'description' => '',                                                                        'price' => 3.00,  'sort_order' => 12],
                ['name' => 'Magnum Sweet & Salty Almond 440ml',          'description' => 'Amandel roomijs met karamel, gezouten karamelsaus en Magnum chocolade',   'price' => 7.50,  'sort_order' => 13],
                ['name' => 'Magnum Double Gold Caramel Billionaire 440ml','description' => 'Roomijs met biscuitsmaak, toffee-kaneelsaus en Golden Caramel chocolade','price' => 7.50,  'sort_order' => 14],
            ],
            default => [],
        };
    }
}
