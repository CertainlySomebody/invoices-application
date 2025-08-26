<?php

namespace Modules\Invoices\Infrastructure\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Modules\Invoices\Application\Services\InvoiceService;
use Modules\Notifications\Api\Events\ResourceDeliveredEvent;

class ResourceDeliveredListener implements ShouldQueue
{

    public $afterCommit = true;
    public function __construct(
        private InvoiceService $invoiceService,
    ) {}

    public function handle(ResourceDeliveredEvent $event): void
    {
        // only if currently sending
        $updated = $this->invoiceService->markAsDelivered($event->resourceId);

        if (!$updated) {
            Log::warning("Delivered event ignored: invoice {$event->resourceId} was not in SENDING state.");
        }
    }
}
