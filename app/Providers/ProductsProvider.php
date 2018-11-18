<?php
namespace App\Providers;

use App\Models\Product\Collection;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

/**
 * Set product repository in the container
 *
 * @package App\Providers
 */
class ProductsProvider extends ServiceProvider {

    /**
     * @inheritdoc
     */
    public $singletons = [
        Collection::class => Collection::class,
    ];
}
