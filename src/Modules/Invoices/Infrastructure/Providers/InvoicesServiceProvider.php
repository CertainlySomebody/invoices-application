<?php


namespace Modules\Invoices\Infrastructure\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Modules\Invoices\Application\Interfaces\InvoiceRepositoryInterface;
use Modules\Invoices\Infrastructure\Console\Commands\SendInvoicesCommand;
use Modules\Invoices\Infrastructure\Listeners\ResourceDeliveredListener;
use Modules\Invoices\Infrastructure\Persistence\InvoiceRepository;
use Modules\Notifications\Api\Events\ResourceDeliveredEvent;

class InvoicesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                SendInvoicesCommand::class,
            ]);
        }

        Event::listen(ResourceDeliveredEvent::class, ResourceDeliveredListener::class);
    }

    public function register(): void
    {
        $this->app->bind(InvoiceRepositoryInterface::class, InvoiceRepository::class);
    }
}

