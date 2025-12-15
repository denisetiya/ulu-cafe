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

    /**
     * Create a new job instance.
     */
    public function __construct(string $promoType, array $promoData)
    {
        $this->promoType = $promoType;
        $this->promoData = $promoData;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Get all customer users (exclude admin, cashier, owner)
        $users = User::whereNotIn('role', ['admin', 'cashier', 'owner'])
            ->whereNotNull('email')
            ->get();

        foreach ($users as $user) {
            Mail::to($user->email)->send(
                new PromoNotification($this->promoType, $this->promoData)
            );
        }
    }
}
