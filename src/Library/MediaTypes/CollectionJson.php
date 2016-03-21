<?php

namespace Scraper\Library\MediaTypes;

use Symfony\Component\HttpFoundation\Response;

class CollectionJson
{
    private $version = '';

    private $href = '';

    private $error = [];

    private $items = [];

    private $links = [];

    /**
     * @param mixed $version
     */
    public function setVersion(string $version)
    {
        $this->version = $version;
    }

    /**
     * @param mixed $href
     */
    public function setHref(string $href)
    {
        $this->href = $href;
    }

    /**
     * @param Response $response
     */
    public function setError(Response $response)
    {
        if ($response->getStatusCode() > 299) {
            $this->error = [
                'data' => $response->getContent(),
                'code' =>  $response->getStatusCode(),
            ];
        }
    }

    /**
     * @param Response $response
     */
    public function setItems(Response $response)
    {
        if ($response->getStatusCode() > 299) {
            return;
        }
        $this->items = json_decode($response->getContent());
    }

    /**
     * @return array
     */
    public function render(): array
    {
        return ['collection' => [
            'version' => $this->version,
            'href' => $this->href,
            'items' => $this->items,
            'links' => $this->links,
            'error' => $this->error,
        ]];
    }
}