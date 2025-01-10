<?php

namespace SimPay\Laravel\Enums\Payment;

enum TransactionStatus: string
{
    case New = 'transaction_new';
    case Generated = 'transaction_generated';
    case Confirmed = 'transaction_confirmed';
    case Paid = 'transaction_paid';
    case Failure = 'transaction_failure';
    case Expired = 'transaction_expired';
    case Refunded = 'transaction_refunded';
}
