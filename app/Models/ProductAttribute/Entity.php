<?php

namespace App\Models\ProductAttribute;


use App\Models\AbstractEntity;

class Entity extends AbstractEntity {

    /**
     * @var int
     */
    public $id;
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $value;
}
