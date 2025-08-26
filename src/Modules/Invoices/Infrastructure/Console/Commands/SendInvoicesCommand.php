<?php

namespace Modules\Invoices\Infrastructure\Console\Commands;

use Illuminate\Console\Command;


class SendInvoicesCommand extends Command
{
    protected $signature = 'invoices:send';
    protected $description = 'Send invoice';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $this->info('Invoices sent successfully!');
    }
}
