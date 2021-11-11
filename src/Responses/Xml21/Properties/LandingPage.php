<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Responses\Xml21\Properties;

use FINDOLOGIC\Api\Definitions\Defaults;
use FINDOLOGIC\Api\Helpers\ResponseHelper;
use SimpleXMLElement;

class LandingPage
{
    private string $link;

    public function __construct(SimpleXMLElement $response)
    {
        $this->link = ResponseHelper::getStringProperty($response, 'link') ?? Defaults::EMPTY;
    }

    public function getLink(): string
    {
        return $this->link;
    }
}
