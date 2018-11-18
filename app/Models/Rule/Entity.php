<?php

namespace App\Models\Rule;

use App\Models\AbstractEntity;
use App\Models\JsonSerializableTrait;

/**
 * Rule entity.
 * 
 * @package App\Models
 */
class Entity extends AbstractEntity implements \JsonSerializable {
    use JsonSerializableTrait;
    
    /**
     * @var string
     */
    public $id;

    /**
     * Friendly name of a rule.
     *
     * @var string
     */
    public $name;

    /**
     * Actual rule string
     *
     * @var string
     */
    public $rule;

    /**
     * Order of rule application. Higher the number, earlier rule will be applied. Usefull with @see $this->stop flag.
     *
     * @var integer
     */
    public $ord;

    /**
     * Whatever to stop rule application after this rule is applied
     *
     * @var bool string
     */
    public $stop                = false;

    /**
     * If this value is true then rule is applied to whole basket instead of individual products.
     *
     * @var bool
     */
    public $basket              = true;
    
    /**
     * Rule start datetime
     *
     * @var \DateTime
     */
    public $since;

    /**
     * Rule end datetime
     *
     * @var \DateTime
     */
    public $until;

    /**
     * If this value is set then if rule evaluates to true then basket price is multiplied by this value.
     *
     * For example to set discount of a basket 20% set this to 0.8
     *
     * @var float
     */
    public $valueMultiplier     = 1;

    /**
     * If this value is set then if rule evaluates to true then basket price is modified by this value.
     *
     * For example to apply discount -100PLN to the basket set this value to -100. To increase basked value set this to 100.
     *
     * @var int
     */
    public $valueModifier       = 0;

    /**
     * If this value is set then if rule evaluates to true following products are added to the basket with price = 0.
     *
     * Example:
     * "additionalProducts": [
     *   {"id": 5, "quantity": 1}
     * ]
     *
     * @var array
     */
    public $additionalProducts  = [];
}


