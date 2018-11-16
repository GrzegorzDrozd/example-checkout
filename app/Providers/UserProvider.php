<?php

namespace App\Providers;

use App\Models\User\Collection;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class UserProvider extends ServiceProvider {

    /**
     * @inheritdoc
     */
    public $singletons = [
        Collection::class => Collection::class,
    ];
}
