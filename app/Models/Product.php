<?php

namespace App\Models;

use App\Enums\ProductStatus;
use MongoDB\Laravel\Eloquent\Model as EloquentModel;

class Product extends EloquentModel
{
    protected $connection = 'mongodb';

    protected $table = 'foods';

    protected $fillable = [
        'code',
        'status',
        'imported_t',
        'url',
        'creator',
        'created_t',
        'last_modified_t',
        'product_name',
        'quantity',
        'brands',
        'categories',
        'labels',
        'cities',
        'purchase_places',
        'stores',
        'ingredients_text',
        'traces',
        'serving_size',
        'serving_quantity',
        'nutriscore_score',
        'nutriscore_grade',
        'main_category',
        'image_url',
    ];

    protected $casts = [
        'status' => ProductStatus::class,
    ];

}


