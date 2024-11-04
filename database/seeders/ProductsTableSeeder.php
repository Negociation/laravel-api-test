<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::connection('mongodb')->table('foods')->insert([
            'code' => 20221126,
            'status' => 'published',
            'url' => 'https://world.openfoodfacts.org/product/20221126',
            'creator' => 'securita',
            'created_t' => 1415302075,
            'last_modified_t' => 1572265837,
            'product_name' => 'Madalenas quadradas',
            'quantity' => '380 g (6 x 2 u.)',
            'brands' => 'La Cestera',
            'categories' => 'Lanches comida, Lanches doces, Biscoitos e Bolos, Bolos, Madalenas',
            'labels' => 'Contem gluten, Contém derivados de ovos, Contém ovos',
            'cities' => '',
            'purchase_places' => 'Braga,Portugal',
            'stores' => 'Lidl',
            'ingredients_text' => 'farinha de trigo, açúcar, óleo vegetal de girassol, clara de ovo, ovo, humidificante (sorbitol), levedantes químicos (difosfato dissódico, hidrogenocarbonato de sódio), xarope de glucose-frutose, sal, aroma',
            'traces' => 'Frutos de casca rija,Leite,Soja,Sementes de sésamo,Produtos à base de sementes de sésamo',
            'serving_size' => 'madalena 31.7 g',
            'serving_quantity' => 31.7,
            'nutriscore_score' => 17,
            'nutriscore_grade' => 'd',
            'main_category' => 'en:madeleines',
            'image_url' => 'https://static.openfoodfacts.org/images/products/20221126/front_pt.5.400.jpg',
            'updated_at' => now(),
            'created_at' => now(),
            'id' => '6726ffea2e98e00b8101cc62'
        ]);
    }
}
