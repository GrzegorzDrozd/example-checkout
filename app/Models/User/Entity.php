<?php
namespace App\Models\User;

use App\Models\AbstractEntity;

/**
 * User entity
 * 
 * @package App\Models
 */
class Entity extends AbstractEntity {

    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $countryCode;

    /**
     * Groups that this user belongs to
     *
     * @var string[]
     */
    public $groups = [];

    /**
     * Tags assigned to this user.
     *
     * @example
     * ['frequent_buyer', 'more_than_10_orders_this_year'],
     *
     * @var string[]
     */
    public $tags = [];

    /**
     * @var \DateTime
     */
    public $created;

    /**
     * @var \DateTime
     */
    public $lastLogin;

    /**
     * @var \DateTime
     */
    public $lastPurchase;
}

