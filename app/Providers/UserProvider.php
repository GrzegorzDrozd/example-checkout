<?php
namespace App\Providers;

use App\Models\User\Collection;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

/**
 * Set user repository in container
 *
 * @package App\Providers
 */
class UserProvider extends ServiceProvider {

    /**
     * @inheritdoc
     */
    public $singletons = [
        Collection::class => Collection::class,
    ];
}
