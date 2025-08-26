<?php

namespace Modules\Invoices\Presentation\Http\Requests;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Invoices\Domain\Entities\Invoice;

class InvoiceResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var Invoice $invoice */
        $invoice = $this->resource;
        return [
            'id' => $invoice->id,
            'customer_name' => $invoice->customerName,
            'customer_email' => $invoice->customerEmail,
            'status' => $invoice->status->value,
            'product_lines' => array_map(fn ($l) => [
                'name' => $l->productName,
                'price' => $l->unitPrice,
                'quantity' => $l->quantity,
                'total_unit_price' => $l->total()
            ], $invoice->productLines),
            'total_price' => $invoice->totalPrice()
        ];
    }
}
