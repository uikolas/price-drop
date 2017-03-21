<?php

namespace App\Parse;

use Money\Money;

class ParseObject
{
    /**
     * @var Money|null
     */
    private $price;

    /**
     * ParseObject constructor.
     * @param Money|null $money
     */
    public function __construct(Money $money = null)
    {
        $this->price = $money;
    }

    /**
     * @return Money|null
     */
    public function getPrice()
    {
        return $this->price;
    }
}
