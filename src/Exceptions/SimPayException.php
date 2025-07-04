<?php

namespace SimPay\Laravel\Exceptions;

use Exception;

class SimPayException extends Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null, public ?string $errorCode = null,)
    {
        parent::__construct($message, $code, $previous);
    }
}
