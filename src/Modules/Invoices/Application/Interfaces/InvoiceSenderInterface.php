<?php

namespace Modules\Invoices\Application\Interfaces;

interface InvoiceSenderInterface
{
    public function send(): void;
}
