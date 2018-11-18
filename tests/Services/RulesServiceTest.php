<?php
use App\Services\RulesService;

/**
 * Class RulesServiceTest
 */
class RulesServiceTest extends \PHPUnit\Framework\TestCase {

    /**
     * @var RulesService
     */
    public $object;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Hoa\Ruler\Ruler
     */
    private $rulerMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\App\Services\Rules\RuleContext
     */
    private $rulerContextMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\App\Models\Rule\Collection
     */
    private $rulerCollectionMock;

    /**
     * 
     */
    public function setUp() {
        $this->rulerMock = $this->getMockBuilder(Hoa\Ruler\Ruler::class)
                                ->disableOriginalConstructor()
                                ->setMethods(['assert'])
                                ->getMock();

        $this->rulerContextMock = $this->getMockBuilder(\App\Services\Rules\RuleContext::class)
                                        ->disableOriginalConstructor()
                                        ->setMethods(['assert'])
                                        ->getMock();

        $this->rulerCollectionMock = $this->getMockBuilder(\App\Models\Rule\Collection::class)
                                            ->disableOriginalConstructor()
                                            ->setMethods(['assert'])
                                            ->getMock();

        $this->object = new RulesService(
            $this->rulerMock,
            $this->rulerContextMock,
            $this->rulerCollectionMock
        );
    }


    /**
     * Test in category operator
     *
     * @param $expected
     * @param $category
     * @param $categories
     * @dataProvider operatorCategoryMatchDataProvider
     */
    public function testOperatorCategoryMatch($expected, $category, $categories) {

        $actual = $this->object->operatorCategoryMatch($category, $categories);

        self::assertSame($expected, $actual);

    }

    /**
     * @return array
     */
    public function operatorCategoryMatchDataProvider() {
        return [
            [true, 'RTV/*',             ['RTV/Telewizory/LCD','RTV/Kino Domowe/Telewizory',],],
            [true, 'RTV/*/LCD',         ['RTV/Telewizory/LCD','RTV/Kino Domowe/Telewizory',],],
            [true, 'RTV/Telewizory/*',  ['RTV/Telewizory/LCD','RTV/Kino Domowe/Telewizory',],],
            [false, 'AGD/Opiekacze/*',  ['RTV/Telewizory/LCD','RTV/Kino Domowe/Telewizory',],],
        ];
    }
}
