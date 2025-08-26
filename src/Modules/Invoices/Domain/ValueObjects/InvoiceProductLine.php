<?php

namespace Modules\Invoices\Domain\ValueObjects;

class InvoiceProductLine
{
    public function __construct(
        public string $productName,
        public int $quantity,
        public int $unitPrice,
    ) {
        if ($this->quantity <= 0 || $this->unitPrice <= 0) {
            throw new \InvalidArgumentException('Quantity must be greater than 0');
        }
    }

    public function total(): int
    {
        return $this->quantity * $this->unitPrice;
    }
}
