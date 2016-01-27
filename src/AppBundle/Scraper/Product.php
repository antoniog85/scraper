<?php

namespace AppBundle\Scraper;

/**
 * Responsible to scrape the products from the sainsburys web page and return a Product collection
 */
class Product extends \AppBundle\Scraper\AbstractScraper {

    /** @var int */
    private $_total = 0;

    /**
     * get the list of product taken from a sainsburys url crawler
     *
     * @param $url
     * @return \AppBundle\Entity\Product[]
     */
    public function getData($url) {
        $pageContent = $this->_curl->getData($url);
        $this->_crawler->addContent($pageContent);
        $products = $this->_crawler->filter('.productLister li')->each(function($productCrawler) {
            $product = $this->_fromCrawlerToEntity($productCrawler);
            $this->_total += $product->getUnitPrice();
            return $product;
        });
        return $products;
    }

    /** @return int */
    public function getTotal() {
        return $this->_total;
    }

    /**
     * make the crawler create a Product entity
     *
     * @param \Symfony\Component\DomCrawler\Crawler $productCrawler
     * @return \AppBundle\Entity\Product
     */
    private function _fromCrawlerToEntity(\Symfony\Component\DomCrawler\Crawler $productCrawler) {
        $link = $productCrawler->filterXPath("//h3/a/@href")->text();
        $pricePerUnit = $productCrawler->filterXPath("//*[contains(@class, 'pricePerUnit')]")->text();

        $product = new \AppBundle\Entity\Product();
        $product->setTitle(trim($productCrawler->filter("h3")->text()));
        $product->setSize($this->_curl->getFileSize($link));
        $product->setUnitPrice($this->_getUnitPriceFromString($pricePerUnit));
        $product->setUrl($link);
        return $product;
    }

    /**
     * @param string $string
     * @return int
     */
    private function _getUnitPriceFromString($string) {
        preg_match_all('!\d+(?:\.\d+)?!', $string, $matches);
        if (isset($matches[0][0])) {
            return $matches[0][0];
        }
        return 0;
    }
}