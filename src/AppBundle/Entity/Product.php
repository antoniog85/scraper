<?php

namespace AppBundle\Entity;

/**
 * Representation of a product entity, as it is on the sainsburys web page
 */
class Product {

    /** @var string */
    private $_title;

    /** @var int */
    private $_size;

    /** @var float */
    private $_unitPrice;

    /** @var string */
    private $_description;

    /** @var string */
    private $_url;

    /** @param string $description */
    public function setDescription($description) {
        $this->_description = $description;
    }

    /** @return string */
    public function getDescription() {
        return $this->_description;
    }

    /** @param int $size */
    public function setSize($size) {
        $this->_size = $size;
    }

    /** @return int */
    public function getSize() {
        return $this->_size;
    }

    /** @param string $title */
    public function setTitle($title) {
        $this->_title = $title;
    }

    /** @return string */
    public function getTitle() {
        return $this->_title;
    }

    /** @param float $unitPrice */
    public function setUnitPrice($unitPrice) {
        $this->_unitPrice = $unitPrice;
    }

    /** @return float */
    public function getUnitPrice() {
        return $this->_unitPrice;
    }

    /** @param string $url */
    public function setUrl($url) {
        $this->_url = $url;
    }

    /** @return string */
    public function getUrl() {
        return $this->_url;
    }

    /** @return array */
    public function toArray() {
        return [
            'title' => $this->getTitle(),
            'size' => $this->getSize(),
            'description' => $this->getDescription(),
            'price_unit' => $this->getUnitPrice(),
        ];
    }
} 