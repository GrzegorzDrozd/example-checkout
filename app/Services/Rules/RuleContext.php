<?php
namespace App\Services\Rules;

use App\Models\User\Entity;
use Hoa\Ruler\Context;

/**
 * Rule context wrapper.
 *
 * Set some basic context variables.
 *
 * @package App\Services\Rules
 */
class RuleContext {

    /**
     * @var \App\Models\Product\Entity[]
     */
    protected $products;

    /**
     * @var Entity
     */
    protected $user;

    /**
     * @var Context
     */
    protected $context;

    /**
     * RuleContext constructor.
     */
    public function __construct() {
        $this->setContext(new Context());
    }

    /**
     * Prepare context.
     *
     * Set default values.
     *
     * @return Context
     */
    public function prepareContext(): Context {

        if ($this->getProducts() === null || $this->getUser() === null) {
            throw new \RuntimeException('This method requires products and user');
        }

        $this->getContext()->offsetSet('user', $this->getUser());
        $this->getContext()->offsetSet('products', $this->getProducts());

        // pre-calculate some additional values based on products in the cart.
        $this->getContext()->offsetSet('all_products_attribute_ids',    $this->getAllProductsAttributeIds());
        $this->getContext()->offsetSet('all_products_categories',       $this->getAllProductsCategories());

        //@todo add more stuff here

        return $this->getContext();
    }

    /**
     * Get unique list of all attribute ids from all products in the cart.
     *
     * @return array
     */
    public function getAllProductsAttributeIds(): array {
        $attributeIds = [];
        foreach ($this->getProducts() as $product) {
            /** @var \App\Models\ProductAttribute\Entity $attribute */
            foreach ($product->attributes as $attribute) {
                $attributeIds[] = $attribute->id;
            }
        }
        return array_unique($attributeIds);
    }

    /**
     * Get unique list of all categories from all products in the cart.
     *
     * @return array
     */
    public function getAllProductsCategories(): array {
        $categories = [];
        foreach ($this->getProducts() as $product) {
            $categories += $product->categories;
        }
        return array_unique($categories);
    }


    /**
     * @return Entity
     */
    public function getUser(): Entity {
        return $this->user;
    }

    /**
     * @param Entity $user
     */
    public function setUser(Entity $user): void {
        $this->user = $user;
    }

    /**
     * @return \App\Models\Product\Entity[]
     */
    public function getProducts(): array {
        return $this->products;
    }

    /**
     * @param \App\Models\Product\Entity[] $products
     */
    public function setProducts(array $products): void {
        $this->products = $products;
    }

    /**
     * @return Context
     */
    public function getContext(): Context {
        return $this->context;
    }

    /**
     * @param Context $context
     */
    public function setContext(Context $context): void {
        $this->context = $context;
    }
}
