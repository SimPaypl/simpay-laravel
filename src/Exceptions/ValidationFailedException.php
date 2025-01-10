<?php

namespace SimPay\Laravel\Exceptions;

use Throwable;

class ValidationFailedException extends SimPayException
{
    public function __construct(private readonly array $validationErrors = [], string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function errors(): array
    {
        return $this->validationErrors;
    }
}
