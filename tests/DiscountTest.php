<?php

use Illuminate\Contracts\Validation\Factory;
use Mateusjatenee\Shoppingcart\Cart;
use Mateusjatenee\Shoppingcart\Contracts\Buyable;
use Mateusjatenee\Shoppingcart\Discount;

class DiscountTest extends Orchestra\Testbench\TestCase
{
    /**
     * Set the package service provider.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [\Mateusjatenee\Shoppingcart\ShoppingcartServiceProvider::class];
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('cart.database.connection', 'testing');

        $app['config']->set('session.driver', 'array');

        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    /** @test */
    public function it_returns_the_discounted_value()
    {
        $discount = $this->getDiscount(5);

        $cart = $this->getCart();

        $item = $this->getBuyableMock();

        $item = $cart->add($item, 3);

        $item->setDiscount($discount);

        $this->assertEquals(5, $item->price);

    }

    /** @test */
    public function it_returns_the_original_value_when_quantity_validation_fails()
    {
        $discount = $this->getDiscount(5, [
            'qty' => 'min:3|max:6',
        ]);

        $cart = $this->getCart();

        $item = $this->getBuyableMock();

        $item = $cart->add($item, 2);

        $item->setDiscount($discount);

        $this->assertEquals(10, $item->price);

        $item->setQuantity(7);

        $this->assertEquals(10, $item->price);
    }

    public function getDiscount($discount = 5, $rules = null)
    {
        $validator = $this->app->make(Factory::class);

        return new Discount($discount, $rules, $validator);
    }

    /**
     * Get an instance of the cart.
     *
     * @return \Mateusjatenee\Shoppingcart\Cart
     */
    private function getCart()
    {
        $session = $this->app->make('session');
        $events = $this->app->make('events');

        $cart = new Cart($session, $events);

        return $cart;
    }

    /**
     * Get a mock of a Buyable item.
     *
     * @param int    $id
     * @param string $name
     * @param float  $price
     *
     * @return \Mockery\MockInterface
     */
    private function getBuyableMock($id = 1, $name = 'Item name', $price = 10.00)
    {
        $item = Mockery::mock(Buyable::class)->shouldIgnoreMissing();

        $item->shouldReceive('getBuyableIdentifier')->andReturn($id);
        $item->shouldReceive('getBuyableDescription')->andReturn($name);
        $item->shouldReceive('getBuyablePrice')->andReturn($price);

        return $item;
    }

}
