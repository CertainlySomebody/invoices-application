<?php

namespace Modules\Invoices\Domain\Entities;

use Modules\Invoices\Domain\Enums\StatusEnum;
use Modules\Invoices\Domain\ValueObjects\InvoiceProductLine;

class Invoice
{
    public function __construct(
        public string $id,
        public string $customerName,
        public string $customerEmail,
        public StatusEnum $status = StatusEnum::Draft,
        /** @var InvoiceProductLine[] */
        public array $productLines = []
    ) {}

    public function addLine(InvoiceProductLine $line): void
    {
        $this->productLines[] = $line;
    }

    public function totalPrice(): int
    {
        return array_sum(array_map(fn($line) => $line->total(), $this->productLines));
    }

    public function canBeSent(): bool
    {
        if ($this->status !== StatusEnum::Draft || empty($this->productLines)) {
            return false;
        }

        foreach ($this->productLines as $line) {
            if ($line->quantity <= 0 || $line->unitPrice <= 0) return false;
        }

        return true;
    }
}
