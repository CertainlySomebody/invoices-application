<?php

declare(strict_types=1);

namespace Modules\Invoices\Presentation\Http;

use Illuminate\Http\JsonResponse;
use Modules\Invoices\Application\Services\InvoiceService;
use Modules\Invoices\Presentation\Http\Requests\InvoiceResource;
use Modules\Invoices\Presentation\Http\Requests\StoreInvoiceRequest;

final readonly class InvoiceController
{
    public function __construct(
        private InvoiceService $invoiceService
    ) {}

    public function index(): JsonResponse
    {
        return new JsonResponse(['init' => 'Invoice controller works!']);
    }

    public function store(StoreInvoiceRequest $request)
    {
        $invoice = $this->invoiceService->createInvoice([
            'customer_name' => $request->string('customer_name')->value(),
            'customer_email' => $request->string('customer_email')->value(),
            'product_lines' => $request->input('product_lines', [])
        ]);

        return new InvoiceResource($invoice);
    }

    public function show(string $id)
    {
        $invoice = $this->invoiceService->viewInvoice($id);
        return new InvoiceResource($invoice);
    }

    /**
     * @throws \Exception
     */
    public function send(string $id)
    {
        $invoice = $this->invoiceService->sendInvoice($id);
        return (new InvoiceResource($invoice))->additional(['message' => 'Invoice is being sent!']);
    }
}
