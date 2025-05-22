<?php

namespace App\Console\Commands;

use App\Helpers\EncryptionHelper;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\PaymentMethod;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixEncryptedData extends Command
{
    protected $signature = 'fix:encrypted-data';
    protected $description = 'Fix any incorrectly encrypted data in the database';

    public function handle()
    {
        $this->info('Starting to fix encrypted data...');

        DB::beginTransaction();

        try {
            // Fix payments
            $this->info('Fixing payments...');
            $payments = Payment::all();
            foreach ($payments as $payment) {
                // Fix amount if it's encrypted
                if (is_string($payment->getRawOriginal('amount')) && EncryptionHelper::isEncrypted($payment->getRawOriginal('amount'))) {
                    $decrypted = EncryptionHelper::decrypt($payment->getRawOriginal('amount'));
                    DB::table('payments')->where('id', $payment->id)->update(['amount' => $decrypted]);
                    $this->info("Fixed amount for payment ID: {$payment->id}");
                }
            }

            // Fix payment methods
            $this->info('Fixing payment methods...');
            $methods = PaymentMethod::all();
            foreach ($methods as $method) {
                // Fix name if it's encrypted
                if (is_string($method->getRawOriginal('name')) && EncryptionHelper::isEncrypted($method->getRawOriginal('name'))) {
                    $decrypted = EncryptionHelper::decrypt($method->getRawOriginal('name'));
                    DB::table('payment_methods')->where('id', $method->id)->update(['name' => $decrypted]);
                    $this->info("Fixed name for payment method ID: {$method->id}");
                }
            }

            DB::commit();
            $this->info('All encrypted data fixed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('An error occurred: ' . $e->getMessage());
        }

        return 0;
    }
}
