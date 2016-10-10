<?php

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
        $this->markTestSkipped();
        $discount = new Discount(5, [
            'quantity' => [
                'maximum' => 10,
                'minimum' => 3,
            ],
        ]);

        $cart = $this->getCart();

        $item = $this->getBuyableMock();

        $item = $cart->add($item, 3);

        $item->setDiscount($discount);

        $this->assertEquals(5, $item->price);

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
