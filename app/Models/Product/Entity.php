<?php

namespace App\Models\Product;

use App\Models\AbstractEntity;

/**
 * Class Entity
 * @package App\Models
 */
class Entity extends AbstractEntity {

    /**
     * @var int
     */
    public $id = 0;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string[]
     */
    public $categories = [];

    /**
     * @var array
     */
    public $tags = [];

    /**
     * @var float
     */
    public $price = 0.0;

    /**
     * @var \App\Models\ProductAttribute\Entity[]
     */
    public $attributes = [];
}

