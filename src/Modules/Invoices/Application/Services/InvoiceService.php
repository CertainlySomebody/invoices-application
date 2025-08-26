<?php

namespace Modules\Invoices\Application\Services;

use Illuminate\Support\Str;
use Modules\Invoices\Application\Interfaces\InvoiceRepositoryInterface;
use Modules\Invoices\Domain\Entities\Invoice;
use Modules\Invoices\Domain\Enums\StatusEnum;
use Modules\Invoices\Domain\ValueObjects\InvoiceProductLine;
use Modules\Notifications\Api\Dtos\NotifyData;
use Modules\Notifications\Api\NotificationFacadeInterface;
use Ramsey\Uuid\Uuid;

class InvoiceService
{
    public function __construct(
        private InvoiceRepositoryInterface $repository,
        private NotificationFacadeInterface $notificationFacade,
    ) {}

    // Create new invoice and product lines if filled properly
    public function createInvoice(array $data): Invoice
    {
        $invoice = new Invoice(
            id: Str::uuid(),
            customerName: $data['customer_name'],
            customerEmail: $data['customer_email'],
            status: StatusEnum::Draft,
            productLines: []
        );

        foreach ($data['product_lines'] ?? [] as $line) {
            $invoice->addLine(
                new InvoiceProductLine($line['product_name'],
                    (int) $line['price'],
                    (int) $line['quantity']
                )
            );
        }

        return $this->repository->save($invoice);
    }

    public function viewInvoice(string $id): Invoice
    {;
        return $this->repository->find($id);
    }

    public function sendInvoice(string $id): Invoice
    {
        $invoice = $this->repository->find($id);

        if (!$invoice->canBeSent()) {
            throw new \Exception("Invoice cannot be sent. It must be in DRAFT status and have valid product lines.");
        }

        // simulate delivery
        $this->notificationFacade->notify(new NotifyData(
            resourceId: Uuid::fromString($invoice->id),
            toEmail: $invoice->customerEmail,
            subject: 'Your invoice is on the way',
            message: "Invoice {$invoice->id} total: {$invoice->totalPrice()}",
        ));

        $this->repository->updateStatus($invoice, StatusEnum::Sending);

        return $this->repository->find($invoice->id);
    }

    public function markAsDelivered(string $id): int
    {
        $invoice = $this->repository->find($id);

        return $this->repository->updateStatus($invoice, StatusEnum::SentToClient);
    }

}
