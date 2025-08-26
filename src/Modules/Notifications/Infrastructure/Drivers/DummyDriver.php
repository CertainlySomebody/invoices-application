<?php

declare(strict_types=1);

namespace Modules\Notifications\Infrastructure\Drivers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Modules\Notifications\Api\Events\ResourceDeliveredEvent;
use Ramsey\Uuid\Uuid;

class DummyDriver implements DriverInterface
{
    public function __construct() {}

    // ses amazon driver implementation
    public function send(
        string $toEmail,
        string $subject,
        string $message,
        string $reference,
    ): bool {

        try {
            Mail::mailer('smtp')->raw($message, function ($m) use ($toEmail, $subject) {
                $m->to($toEmail)->subject($subject);
            });

            dispatch(fn () => event(new ResourceDeliveredEvent(Uuid::fromString($reference))))->delay(5);
        } catch (\Throwable $e) {
            Log::error('SES send failed', [
                'to' => $toEmail,
                'reference' => $reference,
                'exception' => $e,
            ]);
            throw $e;
        }


        return true;
    }
}
