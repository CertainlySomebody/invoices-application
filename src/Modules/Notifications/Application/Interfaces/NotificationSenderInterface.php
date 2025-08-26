<?php

namespace Modules\Notifications\Application\Interfaces;

use Modules\Invoices\Domain\Entities\Invoice;

interface NotificationSenderInterface
{
    public function send(Invoice $invoice): void;
}
