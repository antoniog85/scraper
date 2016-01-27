<?php

namespace AppBundle\Scraper;

/**
 * All the scrapers classes must extend this
 */
abstract class AbstractScraper {

    /** @var \AppBundle\Curl */
    protected $_curl;

    /** @var \Symfony\Component\DomCrawler\Crawler */
    protected $_crawler;

    public function __construct(\AppBundle\Curl $curl, \Symfony\Component\DomCrawler\Crawler $crawler) {
        $this->_curl = $curl;
        $this->_crawler = $crawler;
    }

    /**
     * return the data from the scraper
     *
     * @param $url
     * @return mixed
     */
    abstract public function getData($url);
}