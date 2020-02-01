<?php

namespace App\Tests\Infrastructure\Controller;

use App\Infrastructure\Controller\DiscountController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DiscountControllerTest extends WebTestCase
{
    /**
     * @test
     */
    public function itCanAddDiscount()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            file_get_contents(__DIR__ . '/Fixtures/order.json')
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(
            json_decode(file_get_contents(__DIR__ . '/Fixtures/orderWithDiscounts.json'), true),
            json_decode($client->getResponse()->getContent(), true)
        );
    }

    /**
     * @test
     */
    public function itReturnsErrorOnInvalidJson()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{dlsk,f}'
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals(
            [ 'errors' => [ 'Unable to parse request.' ] ],
            json_decode($client->getResponse()->getContent(), true)
        );
    }

    /**
     * @test
     */
    public function itReturnsErrorsOnInvalidRequest()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            file_get_contents(__DIR__ . '/Fixtures/invalidOrder.json')
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals(
            [
                'errors' => [
                    [
                        'message' => 'product_name must be provided, but does not exist',
                        'property' => 'lines.1.product_name'
                    ],
                    [
                        'message' => 'quantity must be greater than 0',
                        'property' => 'lines.2.quantity'
                    ]
                ]
            ],
            json_decode($client->getResponse()->getContent(), true)
        );
    }
}
