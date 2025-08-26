<?php

namespace Tests\Feature\Invoice\Domain;

use Illuminate\Support\Str;
use Modules\Invoices\Domain\Entities\Invoice;
use Modules\Invoices\Domain\Enums\StatusEnum;
use Modules\Invoices\Domain\ValueObjects\InvoiceProductLine;
use PHPUnit\Framework\TestCase;

class InvoiceTest extends TestCase
{
    public function test_invoice_is_created_as_draft()
    {
        $invoice = new Invoice(
            id: (string) Str::uuid(),
            customerName: 'John Doe',
            customerEmail: 'john@example.com',
            status: StatusEnum::Draft,
            productLines: []
        );

        $this->assertEquals(StatusEnum::Draft, $invoice->status);
        $this->assertEmpty($invoice->productLines);
    }

    public function test_add_product_lines_and_calculate_total()
    {
        $invoice = new Invoice(
            id: (string) Str::uuid(),
            customerName: 'Janine Doe',
            customerEmail: 'janine@example.com',
            status: StatusEnum::Draft,
            productLines: []
        );

        $invoice->addLine(new InvoiceProductLine('Laptop', 1, 2500));
        $invoice->addLine(new InvoiceProductLine('PC', 2, 5000));

        $this->assertCount(2, $invoice->productLines);
        $this->assertEquals(12500, $invoice->totalPrice());
    }

    public function test_invoice_can_be_sent_if_lines_valid()
    {
        $invoice = new Invoice(
            id: (string) Str::uuid(),
            customerName: 'Test',
            customerEmail: 'test@example.com',
            productLines: [new InvoiceProductLine('Laptop', 1, 2500)]
        );

        $this->assertTrue($invoice->canBeSent());
    }
}
