<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Product;

class ProductTest extends \PHPUnit_Framework_TestCase {

    public function testToArrayEmpty() {
        $product = new Product();
        $this->assertEquals(
            [
                'title' => null,
                'size' => null,
                'description' => null,
                'price_unit' => null,
            ],
            $product->toArray()
        );
    }

    /**
     * @dataProvider dataProvider
     */
    public function testTitle($data, $expected) {
        $product = new Product();
        $product->setTitle($data);
        $this->assertEquals($expected, $product->getTitle());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testSize($data, $expected) {
        $product = new Product();
        $product->setSize($data);
        $this->assertEquals($expected, $product->getSize());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testDescription($data, $expected) {
        $product = new Product();
        $product->setDescription($data);
        $this->assertEquals($expected, $product->getDescription());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testPrice($data, $expected) {
        $product = new Product();
        $product->setUnitPrice($data);
        $this->assertEquals($expected, $product->getUnitPrice());
    }

    public function dataProvider() {
        return [
            ['string', 'string'],
            ['', ''],
            [0, 0],
            [535345, 535345],
            ['535345', 535345],
            [5353.45, 5353.45],
            [null, null],
        ];
    }
}
 
