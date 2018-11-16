<?php

namespace App\Models\Rule;

use App\Models\AbstractEntity;
use App\Models\JsonSerializableTrait;

/**
 * Class Entity
 * @package App\Models
 */
class Entity extends AbstractEntity implements \JsonSerializable {
    use JsonSerializableTrait;
    
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $rule;

    /**
     * @var integer
     */
    public $ord;

    /**
     * @var bool string
     */
    public $stop                = false;

    /**
     * @var \DateTime
     */
    public $since;

    /**
     * @var \DateTime
     */
    public $until;

    /**
     * @var float
     */
    public $valueMultiplier     = 1;

    /**
     * @var int
     */
    public $valueModifier       = 0;

    /**
     * @var bool
     */
    public $price               = false;

    /**
     * @var array
     */
    public $additionalProducts  = [];
}


