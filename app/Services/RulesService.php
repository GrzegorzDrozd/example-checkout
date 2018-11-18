<?php
namespace App\Services;

use App\Models\Rule\Collection;
use App\Models\User\Entity;
use App\Services\Rules\RuleContext;
use Hoa\Ruler\Ruler;

/**
 * Process rules.
 *
 * @package App\Services
 */
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
     * @var RuleContext
     */
    protected $context;

    /**
     * RulesService constructor.
     * 
     * @param Ruler $ruler
     * @param RuleContext $context
     * @param Collection $ruleRepository
     */
    public function __construct(Ruler $ruler, RuleContext $context, Collection $ruleRepository) {
        $this->setRuler($ruler);
        $this->setContext($context);
        $this->setRuleRepository($ruleRepository);
    }

    /**
     * Calculate basket value
     *
     * @param \App\Models\Product\Entity[]  $products
     * @param \App\Models\User\Entity       $user
     * @return array
     */
    public function calculateCheckoutPrice(array $products, Entity $user): array {

        // prepare return fields
        $return = [
            'price'                 => 0,
            'price_with_discounts'  => 0,
            'applied_rules'         => [],
            'products'              => [],
        ];

        $ruleContext = $this->getContext();
        $ruleContext->setProducts($products);
        $ruleContext->setUser($user);

        $context     = $ruleContext->prepareContext();

        // products to append to the basket
        $appendBasket   = [];

        // basket multiplier
        $multiplier     = 1;

        // basked modifier
        $modifier       = 0;

        // base time for time calculation
        $t = new \DateTime();

        // iterate over products and add some additional fields to avoid bunch of ifs below
        foreach($products as $product) {
            $product->price_with_discounts  = null;
            $product->applied_rules         = [];
            $product->multiplier            = 1;
            $product->modiffer              = 0;
        }

        /** @var \App\Models\Rule\Entity $rule */
        foreach ($this->getRuleRepository()->getAll(true) as $rule) {

            // we can eliminate rules that are not yet in the effect or are from the past
            // @todo disable rules from the past?
            if ($rule->since !== null && $t < $rule->since) {
                continue;
            }

            if ($rule->until !== null && $t > $rule->until) {
                continue;
            }

            try {
                // check if rule evaluates to true
                $status = $this->getRuler()->assert($rule->rule, $context);
            } catch (\Hoa\Ruler\Exception\Asserter $e) {
                continue;
            }

            if (!$status) {
                continue;
            }

            // add rule to the list of rules applied to the cart.
            $return['applied_rules'][] = $rule->name;
            
            // check if rule adds products to basket
            if (!empty($rule->additionalProducts)) {
                /** @noinspection SlowArrayOperationsInLoopInspection */ // php storm complains about merge in loops
                $appendBasket = array_merge($appendBasket, $rule->additionalProducts);
            }

            // check if we add/subtract value from the cart
            if (!empty($rule->valueModifier)) {
                $modifier += $rule->valueModifier;
            }

            // check if we multiply basket value
            if (!empty($rule->valueMultiplier)) {
                $multiplier = $rule->valueMultiplier;
            }

            // do we stop rule processing?
            if ($rule->stop) {
                break;
            }
        }

        // apply product based rules
        foreach($this->getRuleRepository()->getAll(false) as $rule) {
            // we can eliminate rules that are not yet in the effect or are from the past
            // @todo disable rules from the past?
            if ($rule->since !== null && $t < $rule->since) {
                continue;
            }

            if ($rule->until !== null && $t > $rule->until) {
                continue;
            }

            // iterate over products
            foreach($products as $product) {

                // set current product as a context
                $context['product'] = $product;

                try {
                    // check if rule evaluates to true
                    $status = $this->getRuler()->assert($rule->rule, $context);

                } catch (\Hoa\Ruler\Exception\Asserter $e) {
                    continue;
                }
                // @todo this code is similar to code for basket. Think about extracting it to a method.
                if (!$status) {
                    continue;
                }

                // add rule to the list of rules applied to the cart.
                $product->applied_rules[] = $rule->name;

                // check if rule adds products to basket
                if (!empty($rule->additionalProducts)) {
                    /** @noinspection SlowArrayOperationsInLoopInspection */ // php storm complains about merge in loops
                    $appendBasket = array_merge($appendBasket, $rule->additionalProducts);
                }

                // check if we add/subtract value from the cart
                if (!empty($rule->valueModifier)) {
                    $product->modifier += $rule->valueModifier;
                }

                // check if we multiply basket value
                if (!empty($rule->valueMultiplier)) {
                    $product->multiplier = $rule->valueMultiplier;
                }

                if ($rule->stop) {
                    break;
                }
            }
        }

        foreach ($products as $product) {
            $return['price']                += $product->price * $product->quantity;


            $product->price_with_discount   = round($product->price * $product->multiplier, 2);
            $return['price_with_discounts'] += round(($product->price_with_discount * $product->quantity) * $multiplier, 2);

            $return['products'][]   = [
                'id'                    => $product->id,
                'quantity'              => $product->quantity,
                'applied_rules'         => $product->applied_rules,
                'price_with_discount'   => $product->price_with_discount,
                'price'                 => $product->price
            ];
        }
        $return['products']             = array_merge($return['products'], $appendBasket);
        $return['price_with_discounts'] += $modifier;

        return $return;
    }

    /**
     * Set ruler and add additional operators.
     *
     * @param Ruler $ruler
     */
    public function setRuler(Ruler $ruler): void {
        $inCategoryOperator = new \Hoa\Ruler\Visitor\Asserter();
        $inCategoryOperator->setOperator('in_category', array($this, 'operatorCategoryMatch'));

        $ruler->setAsserter($inCategoryOperator);

        //@todo add more stuff

        $this->ruler = $ruler;
    }

    /**
     * This operator checks if given category is on the list of product categories. It supports wildcard.
     *
     * @example
     *  in_path('RTV/Telewizor/*', product.categories)
     *
     * @param $categoryToMatch
     * @param $categories
     * @return bool
     */
    public function operatorCategoryMatch($categoryToMatch, $categories) : bool {

        // make sure that regular expression is ok
        $categoryToMatch = preg_quote($categoryToMatch, '|');

        // replace \* with .* to support wildcards
        $categoryToMatch = str_replace('\*', '.*', $categoryToMatch);

        // iterate over categories on a list and break on first match
        foreach($categories as $category) {
            if (preg_match('|'.$categoryToMatch.'|', $category)){
                return true;
            }
        }
        return false;
    }

    /**
     * This will display rule in visual way.
     *
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
     * @return RuleContext
     */
    public function getContext(): RuleContext {
        return $this->context;
    }

    /**
     * @param RuleContext $context
     */
    public function setContext(RuleContext $context): void {
        $this->context = $context;
    }

    /**
     * @return Ruler
     */
    public function getRuler(): Ruler {
        return $this->ruler;
    }

    /**
     * @return Collection
     */
    public function getRuleRepository(): Collection {
        return $this->ruleRepository;
    }

    /**
     * @param Collection $ruleRepository
     */
    public function setRuleRepository(Collection $ruleRepository): void {
        $this->ruleRepository = $ruleRepository;
    }
}
