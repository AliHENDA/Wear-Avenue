<?php

namespace App\DataFixtures\Provider;


class SapesProvider {


    private $categories = [
        'Hoodies',
        'Sweatshirts',
        'Pants',
        'Joggers',
        'Jeans',
        'Shirts',
        'T-shirts',
        'Sneakers',
        'Hats',
        'Socks',
        'Vests',
        'Bags',
    ];

    public function productCategory()
    {
        return $this->categories[array_rand($this->categories)];
    }

}