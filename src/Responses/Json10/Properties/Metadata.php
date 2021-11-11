<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Responses\Json10\Properties;

use FINDOLOGIC\Api\Definitions\Defaults;
use FINDOLOGIC\Api\Helpers\ResponseHelper;

class Metadata
{
    private ?LandingPage $landingPage = null;
    private ?Promotion $promotion = null;
    private ?string $searchConcept;
    private int $totalResults;
    private string $currencySymbol;

    /**
     * @param array<string, array<string, string>|string|int|null> $metadata
     */
    public function __construct(array $metadata)
    {
        if (isset($metadata['landingpage']) && is_array($metadata['landingpage'])) {
            $this->landingPage = new LandingPage($metadata['landingpage']);
        }
        if (isset($metadata['promotion']) && is_array($metadata['promotion'])) {
            $this->promotion = new Promotion($metadata['promotion']);
        }

        $this->searchConcept = ResponseHelper::getStringProperty($metadata, 'searchConcept');
        $this->totalResults = ResponseHelper::getIntProperty($metadata, 'totalResults') ?? 0;
        $this->currencySymbol = ResponseHelper::getStringProperty($metadata, 'currencySymbol') ?? Defaults::CURRENCY;
    }

    public function getLandingPage(): ?LandingPage
    {
        return $this->landingPage;
    }

    public function getPromotion(): ?Promotion
    {
        return $this->promotion;
    }

    public function getSearchConcept(): ?string
    {
        return $this->searchConcept;
    }

    public function getTotalResults(): int
    {
        return $this->totalResults;
    }

    public function getCurrencySymbol(): string
    {
        return $this->currencySymbol;
    }
}
