<?php

declare(strict_types=1);

namespace FINDOLOGIC\Api\Requests\SearchNavigation;

use BadMethodCallException;
use FINDOLOGIC\Api\Definitions\QueryParameter;
use FINDOLOGIC\Api\Definitions\RequestMethod;
use FINDOLOGIC\Api\Exceptions\InvalidParamException;
use FINDOLOGIC\Api\Requests\Request;
use FINDOLOGIC\Api\Validators\ParameterValidator;

/**
 * This class holds shared methods and configurations that are commonly used for sending search and navigation requests.
 */
abstract class SearchNavigationRequest extends Request
{
    protected string $method = RequestMethod::GET;

    public function __construct(array $params = [])
    {
        parent::__construct($params);
        $this->addRequiredParams([
            QueryParameter::USER_IP,
            QueryParameter::REVISION,
        ]);
    }

    public function getBody(): ?string
    {
        throw new BadMethodCallException('Request body is not supported for search & navigation requests');
    }

    /**
     * Sets the userip param. It is used for billing and for the user identifier. Required.
     *
     * @see https://docs.findologic.com/doku.php?id=integration_documentation:parameters#required_parameters
     */
    public function setUserIp(string $value): self
    {
        $validator = new ParameterValidator([QueryParameter::USER_IP => $value]);
        $validator->rule('ip', QueryParameter::USER_IP);

        if (!$validator->validate()) {
            throw new InvalidParamException(QueryParameter::USER_IP);
        }

        $this->addParam(QueryParameter::USER_IP, $value);

        return $this;
    }

    /**
     * Sets the referer param. It is used to determine on which page a search was fired.
     *
     * @see https://docs.findologic.com/doku.php?id=integration_documentation:parameters#required_parameters
     */
    public function setReferer(string $value): self
    {
        if (!is_string($value) || !preg_match('/^((^https?:\/\/)|^www\.)/', $value)) {
            throw new InvalidParamException(QueryParameter::REFERER);
        }

        $this->addParam(QueryParameter::REFERER, $value);

        return $this;
    }

    /**
     * Sets the revision param. It is used to identify the version of the plugin. Can be set to 1.0.0 if you are not
     * sure which value should be passed to the API. Required.
     *
     * @see https://docs.findologic.com/doku.php?id=integration_documentation:parameters#required_parameters
     */
    public function setRevision(string $value): self
    {
        $validator = new ParameterValidator([QueryParameter::REVISION => $value]);
        $validator->rule('version', QueryParameter::REVISION);

        if (!$validator->validate()) {
            throw new InvalidParamException(QueryParameter::REVISION);
        }

        $this->addParam(QueryParameter::REVISION, $value);

        return $this;
    }

    /**
     * Adds the attrib param. It is used to filter the search results.
     *
     * @param string|int|float $value
     * @param null|string $specifier is used for sliders such as price. Can be either 'min' or 'max'.
     * @see https://docs.findologic.com/doku.php?id=integration_documentation:parameters#search_parameters
     */
    public function addAttribute(string $filterName, $value, ?string $specifier = null): self
    {
        $validator = new ParameterValidator(['value' => $value]);
        $validator->rule('stringOrNumeric', 'value');

        if (!$validator->validate()) {
            throw new InvalidParamException(QueryParameter::ATTRIB);
        }

        $this->addParam(QueryParameter::ATTRIB, [$filterName => [$specifier => $value]], self::ADD_VALUE);

        return $this;
    }

    /**
     * Sets the order param. It is used to set the order of the products. Please use the given OrderType for setting
     * this value. E.g. OrderType::RELEVANCE for the Findologic relevance.
     *
     * @see https://docs.findologic.com/doku.php?id=integration_documentation:parameters#search_parameters
     */
    public function setOrder(string $value): self
    {
        $validator = new ParameterValidator([QueryParameter::ORDER => $value]);
        $validator->rule('isOrderParam', QueryParameter::ORDER);

        if (!$validator->validate()) {
            throw new InvalidParamException(QueryParameter::ORDER);
        }

        $this->addParam(QueryParameter::ORDER, $value);

        return $this;
    }

    /**
     * Adds the property param. If set the response will display additional data that was exported in this column.
     *
     * @see https://docs.findologic.com/doku.php?id=integration_documentation:parameters#search_parameters
     */
    public function addProperty(string $value): self
    {
        $this->addParam(QueryParameter::PROPERTIES, [$value], self::ADD_VALUE);

        return $this;
    }

    /**
     * Adds the pushAttrib param. It is used to push products based on their attributes and the factor.
     *
     * @see https://docs.findologic.com/doku.php?id=integration_documentation:parameters#search_parameters
     * @see https://docs.findologic.com/doku.php?id=personalization
     * @param string $key Name of the Filter. E.g. Color
     * @param string|int|float $value Value of the Filter. E.g. Black
     * @param float $factor Indicates how much the pushed filter influences the result.
     */
    public function addPushAttrib(string $key, $value, float $factor): self
    {
        $validator = new ParameterValidator(['value' => $value]);
        $validator->rule('stringOrNumeric', ['value']);

        if (!$validator->validate()) {
            throw new InvalidParamException(QueryParameter::PUSH_ATTRIB);
        }

        $this->addParam(QueryParameter::PUSH_ATTRIB, [$key => [$value => $factor]], self::ADD_VALUE);

        return $this;
    }

    /**
     * Sets the first param. It is used for pagination between pages. If your count is for example 20 and you switch
     * to the second page, set this parameter to 20 --> the first product on the next page. Do not set the parameter to
     * 21, because the product listing is 0-based.
     *
     * @see https://docs.findologic.com/doku.php?id=integration_documentation:parameters#limiting_paging_parameters
     */
    public function setFirst(int $value): self
    {
        $validator = new ParameterValidator([QueryParameter::FIRST => $value]);
        $validator->rule('equalOrHigherThanZero', QueryParameter::FIRST);

        if (!$validator->validate()) {
            throw new InvalidParamException(QueryParameter::FIRST);
        }

        $this->addParam(QueryParameter::FIRST, $value);

        return $this;
    }

    /**
     * Sets the identifier param. It is used to display only the item that is given. If this param is set, the query
     * param will be ignored.
     *
     * @see https://docs.findologic.com/doku.php?id=integration_documentation:parameters#limiting_paging_parameters
     * @param string $value ID of the item.
     */
    public function setIdentifier(string $value): self
    {
        $this->addParam(QueryParameter::IDENTIFIER, $value);

        return $this;
    }

    /**
     * Adds the outputAttrib param. Name of the attributes which may be available in the template. Mainly relevant
     * for HTML and JSON output.
     */
    public function addOutputAttrib(string $value): self
    {
        $this->addParam(QueryParameter::OUTPUT_ATTRIB, [$value], self::ADD_VALUE);

        return $this;
    }

    /**
     * Adds the forceOriginalQuery param. It is used for Smart Did-You-Mean. If submitted, the Smart Did-You-Mean
     * functionality is disabled and the search results are based on the user's query.
     *
     * @see https://docs.findologic.com/doku.php?id=integration_documentation:response_xml#forced_query
     */
    public function setForceOriginalQuery(): self
    {
        $this->addParam(QueryParameter::FORCE_ORIGINAL_QUERY, 1);

        return $this;
    }
}
