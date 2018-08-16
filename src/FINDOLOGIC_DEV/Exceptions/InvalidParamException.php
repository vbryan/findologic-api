<?php

namespace FINDOLOGIC_DEV\Exceptions;

use RuntimeException;

class InvalidParamException extends RuntimeException
{
    public function __construct($param)
    {
        parent::__construct(sprintf('Parameter %s is not valid.', $param));
    }
}
