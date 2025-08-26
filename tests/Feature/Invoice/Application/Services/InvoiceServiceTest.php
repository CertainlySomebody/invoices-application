<?php

namespace Tests\Feature\Invoice\Application\Services;

use Illuminate\Support\Str;
use Modules\Invoices\Application\Interfaces\InvoiceRepositoryInterface;
use Modules\Invoices\Application\Services\InvoiceService;
use Modules\Invoices\Domain\Entities\Invoice;
use Modules\Invoices\Domain\Enums\StatusEnum;
use Modules\Invoices\Domain\ValueObjects\InvoiceProductLine;
use Modules\Notifications\Api\NotificationFacadeInterface;
use PHPUnit\Framework\TestCase;
use Mockery;

class InvoiceServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function test_create_invoice_to_repository()
    {
        $repository = Mockery::mock(InvoiceRepositoryInterface::class);
        $notification = Mockery::mock(NotificationFacadeInterface::class);

        $service = new InvoiceService($repository, $notification);

        $repository->shouldReceive('save')->once()->andReturnUsing(fn ($invoice) => $invoice);

        $invoice = $service->createInvoice([
            'customer_name' => 'John Doe',
            'customer_email' => 'john@example.com',
            'product_lines' => [
                ['product_name' => 'laptop', 'quantity' => 1, 'price' => 100],
            ]
        ]);

        $this->assertEquals('John Doe', $invoice->customerName);
        $this->assertCount(1, $invoice->productLines);
        $this->assertEquals(StatusEnum::Draft, $invoice->status);
    }
}
