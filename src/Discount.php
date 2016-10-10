<?php

namespace Mateusjatenee\Shoppingcart;

use Illuminate\Contracts\Validation\Factory as Validator;
use Illuminate\Support\Collection;

class Discount
{
    /**
     * The discount value.
     *
     * @var int|float
     */
    private $value;

    /**
     * The discount rules.
     *
     * @var array
     */
    private $rules;

    /**
     * The associated CartItem.
     *
     * @var \Mateusjatenee\Shoppingcart\CartItem
     */
    private $cartItem;

    /**
     * The validator instance.
     *
     * @var \Illuminate\Contracts\Validation\Factory
     */
    private $validator;

    /**
     * @param $value
     * @param array|null                               $rules
     * @param \Illuminate\Contracts\Validation\Factory $validator
     */
    public function __construct($value, $rules, Validator $validator)
    {
        $this->value = $value;
        $this->rules = new Collection($rules);
        $this->validator = $validator;
    }

    /**
     * Sets the rules of the discount.
     *
     * @param array $rules
     */
    public function setRules(array $rules)
    {
        $this->rules = new Collection($rules);
    }

    /**
     * Gets the discounted value of the item.
     *
     * @param $item
     * @param $price
     *
     * @return int|float
     */
    public function getDiscountedValue($item, $price)
    {
        if ($this->rules && $this->validateRules($item, $price)->fails()) {
            return $price;
        }

        return $price - $this->value;
    }

    /**
     * Validates the given item.
     *
     * @param $item
     * @param $price
     *
     * @return \Illuminate\Validation\Validator
     */
    public function validateRules($item, $price)
    {
        return $this->validator->make($item->toArray(), $this->rules->toArray());
    }
}
