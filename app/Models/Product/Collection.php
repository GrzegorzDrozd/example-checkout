<?php
namespace App\Models\Product;

/**
 * Product collection
 *
 * @package App\Models\Product
 */
class Collection {

    /**
     * @param int[] $entityIds
     * @return Entity[]
     */
    public function getByIds($entityIds){
        // @todo implement correct user storage
        $products = [
            1 => Entity::createFromArray([
                'id'            => 1,
                'name'          => 'Super Duper TV 49',
                'categories'    => [
                    'Super Duper/Telewizory',
                    'RTV/Telewizory/LCD',
                    'RTV/Kino Domowe/Telewizory',
                ],
                'tags'          => [
                    'free_delivery',
                ],
                'price'         => 1000.99,
                'attributes'    => [
                    \App\Models\ProductAttribute\Entity::createFromArray(['id'=>1, 'name'=>'Rozmiar ekranu',    'value'=>49]),
                    \App\Models\ProductAttribute\Entity::createFromArray(['id'=>2, 'name'=>'Ilość złączy hdmi', 'value'=>4]),
                    \App\Models\ProductAttribute\Entity::createFromArray(['id'=>3, 'name'=>'obraz 3d',          'value'=>1]),
                ]
            ]),
            2 => Entity::createFromArray([
                'id'            => 2,
                'name'          => 'Super Duper TV 52',
                'categories'    => [
                    'Super Duper/Telewizory',
                    'RTV/Telewizory/LCD',
                    'RTV/Kino Domowe/Telewizory',
                ],
                'tags'          => [
                    'free_delivery',
                ],
                'price'         => 2000.99,
                'attributes'    => [
                    \App\Models\ProductAttribute\Entity::createFromArray(['id'=>1, 'name'=>'Rozmiar ekranu',    'value'=>52]),
                    \App\Models\ProductAttribute\Entity::createFromArray(['id'=>2, 'name'=>'Ilość złączy hdmi', 'value'=>4]),
                    \App\Models\ProductAttribute\Entity::createFromArray(['id'=>3, 'name'=>'obraz 3d',          'value'=>1]),
                ]
            ]),
            3 => Entity::createFromArray([
                'id'            => 3,
                'name'          => 'Super Duper TV 65',
                'categories'    => [
                    'Super Duper/Telewizory',
                    'RTV/Telewizory/LCD',
                    'RTV/Kino Domowe/Telewizory',
                ],
                'tags'          => [
                    'free_delivery',
                ],
                'price'         => 3000.99,
                'attributes'    => [
                    \App\Models\ProductAttribute\Entity::createFromArray(['id'=>1, 'name'=>'Rozmiar ekranu',    'value'=>65]),
                    \App\Models\ProductAttribute\Entity::createFromArray(['id'=>2, 'name'=>'Ilość złączy hdmi', 'value'=>4]),
                    \App\Models\ProductAttribute\Entity::createFromArray(['id'=>3, 'name'=>'obraz 3d',          'value'=>1]),
                ]
            ]),

            4 => Entity::createFromArray([
                'id'            => 4,
                'name'          => 'MEGA TV 49',
                'categories'    => [
                    'MEGA/Telewizory',
                    'RTV/Telewizory/LCD',
                    'RTV/Kino Domowe/Telewizory',
                ],
                'tags'          => [
                ],
                'price'         => 2010.99,
                'attributes'    => [
                    \App\Models\ProductAttribute\Entity::createFromArray(['id'=>1, 'name'=>'Rozmiar ekranu',    'value'=>49]),
                    \App\Models\ProductAttribute\Entity::createFromArray(['id'=>2, 'name'=>'Ilość złączy hdmi', 'value'=>4]),
                    \App\Models\ProductAttribute\Entity::createFromArray(['id'=>3, 'name'=>'obraz 3d',          'value'=>0]),
                ]
            ]),

            5 => Entity::createFromArray([
                'id'            => 5,
                'name'          => 'Uniwersalne okulary 3d',
                'categories'    => [
                    'RTV/Telewizory/Akcesoria',
                ],
                'tags'          => [
                ],
                'price'         => 100,
                'attributes'    => [
                ]
            ]),
        ];

        $return = [];

        foreach ($entityIds as $id) {
            if (empty($products[$id])) {
                throw new NotFoundException('Product not found');
            }
            $return[$id] = $products[$id];
        }

        return $return;
    }
}
