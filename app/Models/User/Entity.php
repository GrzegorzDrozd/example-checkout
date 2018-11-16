<?php

namespace App\Models\User;

use App\Models\AbstractEntity;

/**
 * Class Entity
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
     * @var string[]
     */
    public $groups = [];

    /**
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

