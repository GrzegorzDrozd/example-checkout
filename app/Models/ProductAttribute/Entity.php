<?php
namespace App\Models\ProductAttribute;

use App\Models\AbstractEntity;

/**
 * Product attribute
 *
 * @package App\Models\ProductAttribute
 */
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
