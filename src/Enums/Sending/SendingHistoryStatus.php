<?php
declare(strict_types=1);

namespace SimPay\Laravel\Enums\Sending;

enum SendingHistoryStatus: string
{
    case Pending = 'history_pending';
    case Delivered = 'history_delivered';
    case Failed = 'history_failed';
    case Busy = 'history_busy';
    case NoAnswer = 'history_no_answer';
    case ToManualVerification = 'history_to_manual_verification';
}
