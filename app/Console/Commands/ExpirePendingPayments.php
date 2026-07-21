<?php

namespace App\Console\Commands;

use App\Services\InventoryReservationService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:expire-pending-payments')]
#[Description('Command description')]
class ExpirePendingPayments extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        app(InventoryReservationService::class)
            ->expirePendingTransactions();

        return self::SUCCESS;
    }
}
