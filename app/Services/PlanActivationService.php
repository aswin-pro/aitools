<?php
namespace App\Services;

use App\Mail\SendEmailInvoice;
use App\Models\Plan;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;

class PlanActivationService
{
    public function activate(
        Collection $config,
        User $user,
        string $transactionId,
        string $paymentId
    ): string {
        // transaction
        $transaction = Transaction::where('transaction_id', $transactionId)
            ->firstOrFail();

        // plan
        $plan     = Plan::where('id', $transaction->plan_id)->firstOrFail();
        $termDays = (int) $plan->validity;

        // Plan validity
        if (empty($user->plan_validity)) {
            $planValidity = Carbon::now()->addDays($termDays);
            $message      = 'Plan activation success!';
        } elseif ($user->plan_id == $transaction->plan_id) {
            $currentValidity = Carbon::parse($user->plan_validity);

            $planValidity = $currentValidity->isFuture()
                ? $currentValidity->addDays($termDays)
                : Carbon::now()->addDays($termDays);

            $message = 'Plan renewed successfully!';
        } else {
            $planValidity = Carbon::now()->addDays($termDays);
            $message      = 'Plan activated successfully!';
        }

        // Invoice number
        $invoiceNumber = Transaction::where(
            'invoice_prefix',
            $config[15]->config_value
        )->count() + 1;

        // Update transaction
        $transaction->update([
            'transaction_id' => $paymentId,
            'invoice_prefix' => $config[15]->config_value,
            'invoice_number' => $invoiceNumber,
            'payment_status' => Transaction::PAYMENT_SUCCESS,
        ]);

        // Update user plan
        $user->plan_id              = $transaction->plan_id;
        $user->term                 = $termDays;
        $user->plan_validity        = $planValidity;
        $user->plan_activation_date = now();
        $user->plan_details         = $plan;
        $user->save();

        // add credits
        addCredits('plan', $plan);

        // Send invoice email
        $this->sendInvoice($config, $transaction, $invoiceNumber);

        return $message;
    }

    private function sendInvoice(
        Collection $config,
        Transaction $transaction,
        int $invoiceNumber
    ): void {
        // invoice
        $invoice = json_decode($transaction->invoice_details, true);

        $details = [
            'from_billing_name'    => $invoice['from_billing_name'],
            'from_billing_email'   => $invoice['from_billing_email'],
            'from_billing_address' => $invoice['from_billing_address'],
            'from_billing_city'    => $invoice['from_billing_city'],
            'from_billing_state'   => $invoice['from_billing_state'],
            'from_billing_country' => $invoice['from_billing_country'],
            'from_billing_zipcode' => $invoice['from_billing_zipcode'],
            'transaction_id'       => $transaction->transaction_id,
            'to_billing_name'      => $invoice['to_billing_name'],
            'to_vat_number'        => $invoice['to_vat_number'],
            'invoice_currency'     => $transaction->transaction_currency,
            'subtotal'             => $invoice['subtotal'],
            'tax_amount'           => $invoice['tax_amount'],
            'invoice_amount'       => $invoice['invoice_amount'],
            'invoice_id'           => $config[15]->config_value . $invoiceNumber,
            'invoice_date'         => $transaction->created_at,
            'description'          => $transaction->description,
            'email_heading'        => $config[27]->config_value,
            'email_footer'         => $config[28]->config_value,
        ];

        try {
            Mail::to($invoice['to_billing_email'])
                ->send(new SendEmailInvoice($details));
        } catch (\Exception $e) {
            // Ignore mail failure
        }
    }
}
