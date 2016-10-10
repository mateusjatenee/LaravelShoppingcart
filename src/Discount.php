<?php

namespace Mateusjatenee\Shoppingcart;

use Illuminate\Support\Collection;

class Discount
{
    private $value;

    private $rules;

    private $cartItem;

    public function __construct($value, $rules = [])
    {
        $this->value = $value;
        $this->rules = $rules;
    }

    public function setRules(array $rules)
    {
        $this->rules = new Collection($rules);
    }

    public function getDiscountedValue($item, $price)
    {
        if (!$this->passesValidation($item, $price)) {
            return $price;
        }

        return $price - $this->value;
    }

    public function passesValidation($item, $price)
    {

        return true;
    }

}
