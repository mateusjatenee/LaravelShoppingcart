<?php

namespace Mateusjatenee\Shoppingcart;

use Illuminate\Contracts\Validation\Factory as Validator;
use Illuminate\Support\Collection;

class Discount
{
    private $value;

    private $rules;

    private $cartItem;

    private $validator;

    public function __construct($value, $rules = null, Validator $validator)
    {
        $this->value = $value;
        $this->rules = $rules;
        $this->validator = $validator;
    }

    public function setRules(array $rules)
    {
        $this->rules = new Collection($rules);
    }

    public function getDiscountedValue($item, $price)
    {
        if ($this->rules && $this->validateRules($item, $price)->fails()) {
            return $price;
        }

        return $price - $this->value;
    }

    public function validateRules($item, $price)
    {
        return $this->validator->make($item->toArray(), $this->rules);
    }

}
