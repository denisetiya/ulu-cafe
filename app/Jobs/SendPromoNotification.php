<?php

namespace App\Jobs;

use App\Mail\PromoNotification;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendPromoNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $promoType;
    public $promoData;
    public $email;

    /**
     * The number of times the job may be attempted.
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public $backoff = 5;

    /**
     * Create a new job instance.
     */
    public function __construct(string $promoType, array $promoData, ?string $email = null)
    {
        $this->promoType = $promoType;
        $this->promoData = $promoData;
        $this->email = $email;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // If specific email is provided, send to that email only
        if ($this->email) {
            Mail::to($this->email)->send(
                new PromoNotification($this->promoType, $this->promoData)
            );
            return;
        }

        // Otherwise, dispatch individual jobs for each user with delay
        $users = User::whereNotIn('role', ['admin', 'cashier', 'owner'])
            ->whereNotNull('email')
            ->get();

        foreach ($users as $index => $user) {
            // Delay each email by 2 seconds to avoid rate limiting (2 req/sec limit)
            self::dispatch($this->promoType, $this->promoData, $user->email)
                ->delay(now()->addSeconds(($index + 1) * 2));
        }
    }
}
