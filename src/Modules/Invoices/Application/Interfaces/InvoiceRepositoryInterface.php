<?php

namespace Modules\Invoices\Application\Interfaces;

use Modules\Invoices\Domain\Entities\Invoice;
use Modules\Invoices\Domain\Enums\StatusEnum;

interface InvoiceRepositoryInterface
{
    public function find(string $id): Invoice;
    public function save(Invoice $invoice): Invoice;
    public function updateStatus(Invoice $invoice, StatusEnum $to): bool;
    public function updateStatusOrFail(Invoice $invoice, StatusEnum $to): Invoice;
}
