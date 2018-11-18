<?php
namespace App\Models\User;

/**
 * Users collection
 * 
 * @package App\Models\User
 */
class Collection {

    /**
     * @param int $userId
     * @return Entity
     */
    public function getById($userId): Entity {
        // @todo implement correct user storage

        $users = [
            1 => Entity::createFromArray([
                'id'            => 1,
                'email'         => 'user_1@domain.com',
                'countryCode'   => 'PL',
                'groups'        => ['registered_users'],
                'tags'          => ['frequent_buyer', 'more_than_10_orders_this_year'],
                'created'       => new \DateTime('2010-10-10 13:13:13'),
                'lastLogin'     => new \DateTime('today noon'),
                'lastPurchase'  => new \DateTime('-2 days'),
            ]),

            2 => Entity::createFromArray([
                'id'            => 2,
                'email'         => 'user_3@domain.com',
                'countryCode'   => 'PL',
                'groups'        => ['registered_users'],
                'tags'          => ['no_orders'],
                'created'       => new \DateTime('today'),
                'lastLogin'     => new \DateTime('today'),
                'lastPurchase'  => null,
            ]),

            3 => Entity::createFromArray([
                'id'            => 3,
                'email'         => 'user_3@domain.com',
                'countryCode'   => 'PL',
                'groups'        => ['registered_users'],
                'tags'          => [''],
                'created'       => new \DateTime('-3 months'),
                'lastLogin'     => new \DateTime('-7 days'),
                'lastPurchase'  => null,
            ]),

            4 => Entity::createFromArray([
                'id'            => 4,
                'email'         => 'user_3@domain.com',
                'countryCode'   => 'PL',
                'groups'        => ['registered_users'],
                'tags'          => ['whale'],
                'created'       => new \DateTime('-2 months'),
                'lastLogin'     => new \DateTime('-2 days'),
                'lastPurchase'  => new \DateTime('-1 month'),
            ]),

        ];

        if (empty($users[$userId])) {
            throw new NotFoundException('User not found');
        }
        return $users[$userId];
    }
}
