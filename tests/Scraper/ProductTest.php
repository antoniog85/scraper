<?php

namespace Tests\Scraper;

class ProductTest extends \PHPUnit_Framework_TestCase {

    public function testGetData() {
        $curl = $this->getMockBuilder('\AppBundle\Curl')
            ->disableOriginalConstructor()
            ->getMock();

        $curl->method('getFileSize')->willReturn(10);
        $curl->method('getData')->willReturn(file_get_contents('productsData.html', FILE_USE_INCLUDE_PATH));

        $productScraper = new \AppBundle\Scraper\Product($curl, new \Symfony\Component\DomCrawler\Crawler());
        $result = $productScraper->getData('');
        $this->assertEquals(7, count($result));
        $this->assertAttributeEquals(15.1, '_total', $productScraper);
    }

    public function testFromCrawlerToEntity() {
        $curl = $this->getMockBuilder('\AppBundle\Curl')
            ->disableOriginalConstructor()
            ->getMock();

        $curl->method('getFileSize')->willReturn(10);
        $productCrawler = new \Symfony\Component\DomCrawler\Crawler(file_get_contents('productData.html', FILE_USE_INCLUDE_PATH));
        $productScraper = new \AppBundle\Scraper\Product($curl, new \Symfony\Component\DomCrawler\Crawler());
        $reflection = new \ReflectionClass('\AppBundle\Scraper\Product');
        $method = $reflection->getMethod('_fromCrawlerToEntity');
        $method->setAccessible(true);
        $product = $method->invoke($productScraper, $productCrawler);
        $this->assertInstanceOf('\AppBundle\Entity\Product', $product);
        $this->assertEquals(10, $product->getSize());
    }

    /**
     * @dataProvider priceProvider
     */
    public function testGetUnitPriceFromString($data, $expected) {
        $curl = $this->getMockBuilder('\AppBundle\Curl')
            ->disableOriginalConstructor()
            ->getMock();
        $productScraper = new \AppBundle\Scraper\Product($curl, new \Symfony\Component\DomCrawler\Crawler());
        $reflection = new \ReflectionClass(get_class($productScraper));
        $method = $reflection->getMethod('_getUnitPriceFromString');
        $method->setAccessible(true);
        $result = $method->invokeArgs($productScraper, [$data]);
        $this->assertEquals($expected, $result);
    }

    public function priceProvider() {
        return [
            ['string', 0],
            ['', 0],
            [0, 0],
            [535345, 535345],
            ['535345', 535345],
            [5353.45, 5353.45],
            [null, 0],
            ['fsdf5435.54gdf', 5435.54],
            ['fsdf5435.54', 5435.54],
            ['5435.54fsdf', 5435.54],
            ['fdsf5435fsdf54fsdf', 5435],
        ];
    }
}
 