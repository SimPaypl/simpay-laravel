<?php

namespace SimPay\Laravel\Enums\DirectBilling;

enum TransactionStatus: string
{
    case New = 'transaction_db_new';
    case Confirmed = 'transaction_db_confirmed';
    case Paid = 'transaction_db_payed';
    case Rejected = 'transaction_db_rejected';
    case Cancelled = 'transaction_db_canceled';
    case GenerateError = 'transaction_db_generate_error';
}
