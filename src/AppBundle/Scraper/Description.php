<?php

namespace AppBundle\Scraper;

/**
 * Get the description of a product from its url
 */
class Description extends AbstractScraper {

    /**
     * @param $url
     * @return string
     */
    public function getData($url) {
        $pageContent = $this->_curl->getData($url);
        $this->_crawler->addContent($pageContent);
        $productDom = $this->_crawler->filterXPath("//*[contains(@class, 'productText')]/p");
        return $productDom->text();
    }
} 