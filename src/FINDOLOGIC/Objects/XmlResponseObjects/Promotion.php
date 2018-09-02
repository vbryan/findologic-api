<?php

namespace FINDOLOGIC\Objects\XmlResponseObjects;

use SimpleXMLElement;

class Promotion
{
    /** @var string $image */
    private $image;

    /** @var string $link */
    private $link;

    /**
     * Promotion constructor.
     * @param SimpleXMLElement $response
     */
    public function __construct($response)
    {
        $this->image = (string)$response->image;
        $this->link = (string)$response->link;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }
}