<?php
namespace App\Http\Controllers\Checkout;

use Illuminate\Http\Request;

/**
 * Handle checkout request.
 *
 * Uses post http message body for backet content.
 * Expects following data:
 *
 * {"products": [{"id": 1, "quantity": 1}], "user": 1}
 *
 * OR
 *
 * {"products": [{"id": 1, "quantity": 1}, {"id": 2, "quantity": 1}], "user": 1}
 *
 * Where products contains an array of product id and quantity and user contains user id.
 *
 * @package App\Http\Controllers\Checkout
 */
class Post extends \App\Http\Controllers\Controller {

    /**
     * @var \App\Models\User\Collection
     */
    protected $userRepository;

    /**
     * @var \App\Models\Product\Collection
     */
    protected $productRepository;

    /**
     * @var \App\Services\RulesService
     */
    protected $rulesService;

    /**
     * Post constructor.
     * 
     * @param \App\Models\User\Collection $userCollection
     * @param \App\Models\Product\Collection $productCollection
     * @param \App\Services\RulesService $rulesService
     */
    public function __construct(
        \App\Models\User\Collection $userCollection,
        \App\Models\Product\Collection $productCollection,
        \App\Services\RulesService $rulesService

    ) {
        $this->userRepository       = $userCollection;
        $this->productRepository    = $productCollection;
        $this->rulesService         = $rulesService;
    }

    /**
     * Request handling.
     *
     * Returns whole basket value, whole basket value after rules, list of products with base price, price after
     * modifications, quantity and list of applied rules.
     *
     * Example response:
     * @example
     *
     * {
     *   "price": 1000.99,
     *   "price_with_discounts": 900.89,
     *   "applied_rules": [
     *     "darmowe okulary do teleizora 3d!"
     *   ],
     *   "products": [
     *     {
     *       "id": 1,
     *       "quantity": 1,
     *       "applied_rules": [
     *         "-10% na telewizor!"
     *       ],
     *       "price_with_discount": 900.89,
     *       "price": 1000.99
     *     },
     *     {
     *       "id": 5,
     *       "quantity": 1
     *     }
     *   ]
     * }
     * 
     * @param Request $request
     * @return array
     */
    public function index(Request $request) : array {

        // read request from request body
        $basket = $request->input();

        // get user to allow rules to use user metadata
        $user = $this->userRepository->getById($basket['user']);

        // get products ids for one lookup vs lookup by id
        $productIds = array_unique(array_column($basket['products'], 'id'));

        // list of products in the basket
        $productsInformationByProductId = $this->productRepository->getByIds($productIds);

        // add quantity to product list. 
        $productsWithQuantityByProductId = [];
        foreach($basket['products'] as $product) {
            $productsWithQuantityByProductId[$product['id']] = $productsInformationByProductId[$product['id']];
            $productsWithQuantityByProductId[$product['id']]->quantity = $product['quantity'];
        }
        $calculatedBasketValue = $this->rulesService->calculateCheckoutPrice($productsWithQuantityByProductId, $user);

        return [
            'price'                 => $calculatedBasketValue['price'],
            'price_with_discounts'  => $calculatedBasketValue['price_with_discounts'],
            'applied_rules'         => $calculatedBasketValue['applied_rules'],
            'products'              => $calculatedBasketValue['products']
        ];
    }
}
