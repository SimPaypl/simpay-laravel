<?php
declare(strict_types=1);

namespace SimPay\Laravel\Enums\Sending;

enum SendingMassStatus: string
{
    case Pending = 'mass_pending';
    case InProgress = 'mass_in_progress';
    case Completed = 'mass_completed';
    case Failed = 'mass_failed';
    case Cancelled = 'mass_cancelled';
}
