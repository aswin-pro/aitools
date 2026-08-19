<?php

namespace App\Http\Controllers\Admin;

use App\Models\Config;
use App\Models\Setting;
use App\Models\Currency;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateCurrencyRequest;
use App\Http\Requests\Admin\UpdateCurrencyRequest;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class CurrencyController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    // All Currencies
    public function currencies(Request $request)
    {
        $currencies = Currency::where('status', 1)
            ->when($request->search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('iso_code', 'like', "%{$search}%")
                        ->orWhere('symbol', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('priority')
            ->paginate(
                $request->integer('per_page', 10)
            )
            ->withQueryString();

        $config = Config::get();
        $settings = Setting::where('status', 1)->first();

        return Inertia::render('admin/currencies/index', compact('config', 'settings', 'currencies'));
    }


    public function createCurrency(CreateCurrencyRequest $request)
    {
        $validated = $request->validated();

        $priority = $validated['priority']
            ?? ((Currency::max('priority') ?? 0) + 1);

        Currency::create([
            'priority' => $priority,
            'name' => $validated['name'],
            'iso_code' => strtoupper($validated['iso_code']),
            'iso_numeric' => $validated['iso_numeric'],
            'symbol' => $validated['symbol'],
            'subunit' => $validated['subunit'] ?? '',
            'subunit_to_unit' => $validated['subunit_to_unit'] ?? '',
            'symbol_first' => $validated['symbol_first'],
            'html_entity' => $validated['html_entity'] ?? '',
            'decimal_mark' => $validated['decimal_mark'] ?? '.',
            'thousands_separator' => $validated['thousands_separator'] ?? ',',
            'status' => 1,
        ]);

        return redirect()
            ->route('dashboard.admin.currencies')
            ->with('success', __('Currency created successfully.'));
    }


    public function updateCurrency(UpdateCurrencyRequest $request)
    {
        $validated = $request->validated();

        $currency = Currency::find($validated['id']);

        if (!$currency) {
            return redirect()
                ->route('dashboard.admin.currencies')
                ->with('error', __('Currency not found!'));
        }

        // If priority is empty, keep the existing priority.
        $priority = $validated['priority'] ?? $currency->priority;

        $currency->update([
            'priority' => $priority,
            'name' => $validated['name'],
            'iso_code' => strtoupper($validated['iso_code']),
            'iso_numeric' => $validated['iso_numeric'] ?? '',
            'symbol' => $validated['symbol'],
            'subunit' => $validated['subunit'] ?? '',
            'subunit_to_unit' => $validated['subunit_to_unit'] ?? '',
            'symbol_first' => $validated['symbol_first'],
            'html_entity' => $validated['html_entity'] ?? "",
            'decimal_mark' => $validated['decimal_mark'] ?? '.',
            'thousands_separator' => $validated['thousands_separator'] ?? ',',
        ]);

        return redirect()
            ->route('dashboard.admin.currencies')
            ->with('success', __('Currency updated successfully!'));
    }

    //Delete Currency
    public function deleteCurrency(Request $request)
    {
        $id = $request->query('id');

        $currency = Currency::where('id', $id)->first();

        if (!$currency) {
            return redirect()->route('admin.currencies')
                ->with('failed', __('Currency not found!'));
        }

        // Check if this is the last active currency
        $activeCount = Currency::where('status', 1)->count();
        if ($activeCount <= 1 && $currency->status == 1) {
            return redirect()->route('admin.currencies')
                ->with('failed', __('Unable to delete currency. Please keep at least one active currency.'));
        }

        // Soft delete (set status = 0)
        Currency::where('id', $id)->update(['status' => 0]);

        return redirect()->route('admin.currencies')
            ->with('success', __('Currency deleted successfully.'));
    }
}
