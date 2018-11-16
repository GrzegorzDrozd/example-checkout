<?php

namespace App\Services;


use App\Models\Rule\Collection;
use App\Models\User\Entity;
use Hoa\Ruler\Context;
use Hoa\Ruler\Ruler;

class RulesService {

    /**
     * @var Ruler
     */
    protected $ruler;

    /**
     * @var Collection 
     */
    protected $ruleRepository;


    /**
     * RulesService constructor.
     * @param Ruler $ruler
     * @param Collection $ruleRepository
     */
    public function __construct(Ruler $ruler, Collection $ruleRepository) {
        $this->ruler = $ruler;

        $this->ruleRepository = $ruleRepository;
    }

    /**
     * @param array                         $products
     * @param \App\Models\User\Entity       $user
     * @return array
     */
    public function calculateCheckoutPrice(array $products, Entity $user): array {

        $return = [
            'price'                 => 0,
            'price_with_discounts'  => 0,
            'applied_rules'         => [],
            'products'              => [],
        ];


        $context        = $this->getContext($products, $user);
        
        $appendBasket   = [];
        $appliedRules   = [];
        $multiplier     = 1;
        $modifier       = 0;

        $t = new \DateTime();
        foreach ($this->ruleRepository->getAll() as $rule) {

            // we can eliminate rules that are not yet in the effect or are from the past
            // @todo disable rules from the past?
            if (!empty($rule->since) and $t < $rule->since) {
                continue;
            }

            if (!empty($rule->until) and $t > $rule->until) {
                continue;
            }

            $status = $this->ruler->assert($rule->rule, $context);

            if ($status) {
                $appliedRules[] = $rule->name;
                if (!empty($rule->additionalProducts)) {
                    /** @noinspection SlowArrayOperationsInLoopInspection */
                    $appendBasket = array_merge($appendBasket, $rule->additionalProducts);
                }

                if (!empty($rule->valueModifier)) {
                    $modifier += $rule->valueModifier;
                }

                if (!empty($rule->valueMultiplier)) {
                    $multiplier = $rule->valueMultiplier;
                }

                if ($rule->stop) {
                    break;
                }
            }
        }

        foreach ($products as $product) {
            $return['price']                += $product['product']->price*$product['quantity'];
            $return['price_with_discounts'] += round(($product['product']->price* $product['quantity']) * $multiplier, 2);
            $return['products'][]           = [
                'product'                           => $product['product'],
                'quantity'                          => $product['quantity'],
            ];
            $return['applied_rules']        = $appliedRules;
        }
        $return['products'] = array_merge($return['products'], $appendBasket);
        $return['price_with_discounts'] += $modifier;

        return $return;
    }

    /**
     * @param string $rule
     */
    private function debugRule(string $rule): void {
        $compiler    = \Hoa\Compiler\Llk\Llk::load(
            new \Hoa\File\Read('hoa://Library/Ruler/Grammar.pp')
        );
        $ast         = $compiler->parse($rule);
        $interpreter = new \Hoa\Ruler\Visitor\Interpreter();
        $model       = $interpreter->visit($ast);

        $compiler = new \Hoa\Ruler\Visitor\Compiler();
        $compiler->visit($model);
    }

    /**
     * @param array $products
     * @param Entity $user
     * @return Context
     */
    private function getContext(array $products, Entity $user): Context {
        $context                               = new Context();
        $context['user']                       = $user;
        $context['products']                   = $products;
        $context['all_products_attribute_ids'] = function () use ($products) {
            foreach ($products as $product) {
                $attributeIds = [];
                /** @var \App\Models\ProductAttribute\Entity $attribute */
                foreach ($product['product']->attributes as $attribute) {
                    $attributeIds[] = $attribute->id;
                }
                return array_unique($attributeIds);
            }
        };
        return $context;
    }
}
