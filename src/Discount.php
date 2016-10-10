<?php

namespace Mateusjatenee\Shoppingcart;

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
        $this->rules = $rules;
    }

}
