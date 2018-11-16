<?php

namespace App\Http\Controllers\Checkout;

use Illuminate\Http\Request;

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
     * @param \App\Models\User\Collection $userCollection
     * @param \App\Models\Product\Collection $productCollection
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
     * @param Request $request
     * @return array
     */
    public function index(Request $request){
        $basket = $request->input();

        // get user to allow rules to use user metadata
        $user = $this->userRepository->getById($basket['user']);

        // get products ids for one lookup vs lookup by id
        $productIds = array_column($basket['products'], 'id');
        $productIds = array_unique($productIds);

        // list of products in the basket
        // @todo replace with db call
        $productsInformationByProductId = $this->productRepository->getByIds($productIds);

        $basketWithQuantity = [];
        foreach($basket['products'] as $product) {
            $basketWithQuantity[] = [
                'product' => $productsInformationByProductId[$product['id']],
                'quantity'=> $product['q'],
            ];
        }

        $calculatedBasketValues = $this->rulesService->calculateCheckoutPrice($basketWithQuantity, $user);
        return [
            'price'                 => $calculatedBasketValues['price'],
            'price_with_discounts'  => $calculatedBasketValues['price_with_discounts'],
            'applied_rules'         => $calculatedBasketValues['applied_rules'],
            'products'              => $calculatedBasketValues['products']
        ];
    }
}
