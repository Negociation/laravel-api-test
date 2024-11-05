<?php

namespace App\Models;

use App\Enums\ProductStatus;
use MongoDB\Laravel\Eloquent\Model as EloquentModel;

class Product extends EloquentModel
{
    protected $connection = 'mongodb';

    protected $table = 'foods';

    protected $guarded = [];

    protected $casts = [
        'status' => ProductStatus::class,
    ];

}


