<?php

namespace Modules\Notifications\Infrastructure\Console\Commands;

use Illuminate\Console\Command;

class SendNotificationsCommand extends Command
{
    protected $signature = 'notifications:send';
    protected $description = 'Send Notification';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $this->info('Sending notification...');
    }
}
