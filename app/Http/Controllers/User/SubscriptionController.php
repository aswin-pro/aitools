<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ContentTemplate;
use App\Models\Plan;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SubscriptionController extends Controller
{
    // index
    public function index(Request $request): Response
    {
        // return view
        return Inertia::render('user/subscriptions/index', [
            'transactions' => fn() => Transaction::dataWithPagination(
                search: $request->search,
                perPage: $request->integer('per_page', 10),
                with: ['plan'],
                scope: 'user'
            ),
        ]);
    }

    // plans
    public function plans(): Response
    {
        // plans
        $plans = Plan::activePlans();

        // templates with category
        $templates = ContentTemplate::getData(with: ['category']);

        // return view
        return Inertia::render('user/subscriptions/plans/index', ['plans' => $plans, 'templates' => $templates]);
    }

    //  View Invoice
    public function invoice(string $id): Response
    {
        // transaction details
        $transaction = Transaction::where('id', $id)->first();

        // billing details
        $transaction['billing_details'] = json_decode($transaction['invoice_details'], true);

        // return view
        return Inertia::render('user/subscriptions/invoice/index', [
            'transaction' => $transaction,
        ]);
    }
}
