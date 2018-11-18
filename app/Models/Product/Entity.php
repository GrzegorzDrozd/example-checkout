<?php
namespace App\Models\Product;

use App\Models\AbstractEntity;

/**
 * Product entity
 *
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
     * List of categories that this product belogs to.
     *
     * @example
     * 'Super Duper/Telewizory',
     * 'RTV/Telewizory/LCD',
     * 'RTV/Kino Domowe/Telewizory',
     *
     * @var string[]
     */
    public $categories = [];

    /**
     * List of tags for product.
     *
     * For @example:
     * 'large',
     * 'replacement_package'
     *
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

