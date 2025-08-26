<?php

namespace Modules\Invoices\Infrastructure\Persistence;

use Illuminate\Support\Facades\Log;
use Modules\Invoices\Application\Interfaces\InvoiceRepositoryInterface;
use Modules\Invoices\Domain\Entities\Invoice;
use App\Models\Invoice as InvoiceModel;
use Modules\Invoices\Domain\ValueObjects\InvoiceProductLine;
use Modules\Invoices\Domain\Enums\StatusEnum;

class InvoiceRepository implements InvoiceRepositoryInterface
{
    public function save(Invoice $invoice): Invoice
    {
        $m = InvoiceModel::find($invoice->id);
        if (!$m) {
            $m = new InvoiceModel();
            $m->id = $invoice->id;
        }

        $m->customer_name = $invoice->customerName;
        $m->customer_email = $invoice->customerEmail;
        $m->status = $invoice->status->value;
        $m->save();

        foreach ($invoice->productLines as $line) {
            $m->productLines()->create([
                'invoice_id' => $invoice->id,
                'name' => $line->productName,
                'price' => $line->unitPrice,
                'quantity' => $line->quantity,
            ]);
        }

        return self::find($m->id);

    }

    /** Retrieve invoice by id */
    public function find(string $id): Invoice
    {
        /** @var InvoiceModel $m */
        $m = InvoiceModel::with('productLines')->findOrFail($id);

        $lines = [];



        foreach ($m->productLines()->getResults() as $pl) {
            ;
            $lines[] = new InvoiceProductLine(
                productName: $pl->name,
                quantity: (int)$pl->quantity,
                unitPrice: (int)$pl->price
            );
        }


        return new Invoice(
            id: $m->id,
            customerName: $m->customer_name,
            customerEmail: $m->customer_email,
            status: $m->status,
            productLines: $lines
        );
    }

    public function updateStatus(Invoice $invoice, StatusEnum $to): bool
    {
        $update = InvoiceModel::query()
            ->where('id', $invoice->id)
            ->where('status', $invoice->status->value)
            ->update(['status' => $to->value]);

        if ($update) {
            Log::info("Invoice {$invoice->id} status updated.", [
                'id' => $invoice->id,
                'to' => $invoice->customerEmail
            ]);
        } else {
            Log::warning("Invoice {$invoice->id} status update failed.", [
                'id' => $invoice->id,
                'to' => $invoice->customerEmail
            ]);
        }

        return $update === 1;
    }

    public function updateStatusOrFail(Invoice $invoice, StatusEnum $to): Invoice
    {
        $update = $this->updateStatus($invoice, $to);
        if ($update) {
            throw new \RuntimeException("Status transition {$invoice->status->value} to {$invoice->status->value} failed for invoice {$invoice->id}");
        }

        return $this->find($invoice->id);
    }



}
