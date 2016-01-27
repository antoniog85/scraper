<?php
namespace AppBundle\Command;

use \Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProductCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
            ->setName('scraper:product')
            ->setDescription('Get a json list of product scraped from a Sainsburys page');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $data = [];
        $productUrl = 'http://hiring-tests.s3-website-eu-west-1.amazonaws.com/2015_Developer_Scrape/5_products.html';
        $curl = $this->getContainer()->get('app.curl');
        $productScraper = new \AppBundle\Scraper\Product($curl, new \Symfony\Component\DomCrawler\Crawler());
        $products = $productScraper->getData($productUrl);
        foreach ($products as $product) {
            $descriptionScraper = new \AppBundle\Scraper\Description($curl, new \Symfony\Component\DomCrawler\Crawler());
            $product->setDescription($descriptionScraper->getData($product->getUrl()));
            $data[] = $product->toArray();
        }
        $output->writeln(json_encode(['results' => $data, 'total' => $productScraper->getTotal()]));
    }
}