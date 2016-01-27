<?php

namespace Tests\Scraper;

class DescriptionTest extends \PHPUnit_Framework_TestCase {

    public function testGetData() {
        $curl = $this->getMockBuilder('\AppBundle\Curl')
            ->disableOriginalConstructor()
            ->getMock();

        $curl->method('getData')->willReturn(file_get_contents('descriptionData.html', FILE_USE_INCLUDE_PATH));

        $scraper = new \AppBundle\Scraper\Description($curl, new \Symfony\Component\DomCrawler\Crawler());
        $result = $scraper->getData('');
        $this->assertEquals('Apricots', $result);
    }
}
 