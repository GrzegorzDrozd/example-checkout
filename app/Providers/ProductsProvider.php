<?php

namespace App\Providers;

use App\Models\Product\Collection;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class ProductsProvider extends ServiceProvider {

    /**
     * @inheritdoc
     */
    public $singletons = [
        Collection::class => Collection::class,
    ];
}
