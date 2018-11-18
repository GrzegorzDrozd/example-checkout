<?php

use App\Services\Rules\RuleContext;
use PHPUnit\Framework\TestCase;

/**
 * Class RuleContextTest
 */
class RuleContextTest extends TestCase {

    /**
     * @var RuleContext
     */
    public $object;
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Hoa\Ruler\Context
     */
    private $contextMock;

    /**
     * 
     */
    public function setUp(){

        $this->contextMock = $this->getMockBuilder(\Hoa\Ruler\Context::class)
                                  ->disableOriginalConstructor()
                                  ->setMethods(['offsetGet'])
                                  ->getMock();

        $this->object = new RuleContext();
        $this->object->setContext($this->contextMock);
    }

    /**
     * Test extracting attribute ids from products
     */
    public function testGetAllProductsAttributeIds() : void {
        $this->object->setProducts([
            \App\Models\Product\Entity::createFromArray([
                'id'=>1,
                'attributes'=>[
                    \App\Models\ProductAttribute\Entity::createFromArray(['id'=>1])
                ]
            ])
        ]);

        $actual = $this->object->getAllProductsAttributeIds();
        self::assertSame([1], $actual);
    }

    /**
     * Test extracting categories from products
     */
    public function testGetAllProductsCategories() : void {
        $this->object->setProducts([
            \App\Models\Product\Entity::createFromArray([
                'id'=>1,
                'categories'    => ['foo', 'bar']
            ]),
            \App\Models\Product\Entity::createFromArray([
                'id'=>2,
                'categories'    => ['bar', 'baz']
            ])
        ]);

        $actual = $this->object->getAllProductsCategories();
        self::assertSame(['foo', 'bar', 'baz'], array_values($actual));
    }
}
