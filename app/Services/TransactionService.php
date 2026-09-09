<?php

namespace App\Services;

use App\Models\Plan;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Collection;

class TransactionService
{
    // Generate invoice details array
    public function generateInvoiceDetails(
        Collection $config,
        User $user,
        Plan $plan,
        float $amount
    ): array {
        $taxValue = (float) $config[25]->config_value;
        $taxAmount = getTaxPrice($config, $amount);

        return [
            'from_billing_name'    => $config[16]->config_value,
            'from_billing_address' => $config[19]->config_value,
            'from_billing_city'    => $config[20]->config_value,
            'from_billing_state'   => $config[21]->config_value,
            'from_billing_zipcode' => $config[22]->config_value,
            'from_billing_country' => $config[23]->config_value,
            'from_vat_number'      => $config[26]->config_value,
            'from_billing_phone'   => $config[18]->config_value,
            'from_billing_email'   => $config[17]->config_value,

            'to_billing_name'      => $user->billing_name,
            'to_billing_address'   => $user->billing_address,
            'to_billing_city'      => $user->billing_city,
            'to_billing_state'     => $user->billing_state,
            'to_billing_zipcode'   => $user->billing_zipcode,
            'to_billing_country'   => $user->billing_country,
            'to_billing_phone'     => $user->billing_phone,
            'to_billing_email'     => $user->billing_email,
            'to_vat_number'        => $user->vat_number,

            'tax_name'             => $config[24]->config_value,
            'tax_type'             => $config[14]->config_value,
            'tax_value'            => $taxValue,
            'invoice_amount'       => $amount,
            'subtotal'             => $plan->price,
            'tax_amount'           => $taxAmount,
        ];
    }

    // Create pending transaction
    public function createPendingTransaction(
        Collection $config,
        User $user,
        Plan $plan,
        string $transactionId,
        float $amount,
        string $gateway
    ): Transaction {
        return Transaction::create([
            'transaction_date'      => now(),
            'transaction_id'        => $transactionId,
            'user_id'               => $user->id,
            'plan_id'               => $plan->id,
            'description'           => "{$plan->name} Plan",
            'payment_gateway_name'  => $gateway,
            'transaction_amount'    => $amount,
            'transaction_currency'  => $config[1]->config_value,
            'invoice_details'       => json_encode(
                $this->generateInvoiceDetails($config, $user, $plan, $amount)
            ),
            'payment_status'        => 'PENDING',
        ]);
    }
}