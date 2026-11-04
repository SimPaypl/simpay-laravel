<?php
declare(strict_types=1);

namespace SimPay\Laravel\Enums\Sending;

enum SendingFailureReason: string
{
    case ProviderError = 'provider_error';
    case OverrideNotFound = 'override_not_found';
    case ContentRejected = 'content_rejected';
}
