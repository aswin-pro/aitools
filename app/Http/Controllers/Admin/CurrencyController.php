<?php

namespace App\Http\Controllers\Admin;

use App\Models\Config;
use App\Models\Setting;
use App\Models\Currency;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
            ->orderBy('priority')
            ->paginate(
                $request->integer('per_page', 10)
            )
            ->withQueryString();

        $config = Config::get();
        $settings = Setting::where('status', 1)->first();

        // if ($request->ajax()) {
        //     return DataTables::of($currencies)
        //         ->addIndexColumn('id')
        //         ->addColumn('iso_code', function ($row) {
        //             return $row->iso_code;
        //         })
        //         ->addColumn('name', function ($row) {
        //             return '<a href="' . route('dashboard.admin.edit.currency', $row->id) . '">' . $row->name . '</a>';
        //         })
        //         ->addColumn('symbol', function ($row) {
        //             return $row->symbol;
        //         })
        //         ->addColumn('symbol_first', function ($row) {
        //             return $row->symbol_first == 'false' ?
        //                 '<span class="badge bg-red text-white">' . __('No') . '</span>' :
        //                 '<span class="badge bg-green text-white">' . __('Yes') . '</span>';
        //         })
        //         ->addColumn('status', function ($row) {
        //             return '<span class="badge bg-green text-white">' . __('Activated') . '</span>';
        //         })
        //         ->addColumn('action', function ($row) {
        //             $editUrl = route('dashboard.admin.edit.currency', $row->id);
        //             $activateDeactivate = $row->status == 0 ? trans('Activate') : trans('Deactivate');
        //             $activateDeactivateFunction = $row->status == 0 ? 'activateCurrency' : 'deactivateCurrency';

        //             return '
        //                 <a class="btn small-btn dropdown-toggle align-text-top" href="#" role="button" data-bs-boundary="viewport" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">' . __('Actions') . '</a>
        //                 <div class="dropdown-menu dropdown-menu-end">
        //                     <a class="dropdown-item" href="' . $editUrl . '">' . __('Edit') . '</a>
        //                     <a class="dropdown-item text-danger" href="#" onclick="deleteCurrency(`' . $row->id . '`); return false;">' . __('Delete') . '</a>
        //                 </div>';
        //         })
        //         ->rawColumns(['name', 'iso_code', 'symbol', 'symbol_first', 'status', 'action'])
        //         ->make(true);
        // }


        return Inertia::render('admin/currencies/index', compact('config', 'settings', 'currencies'));
    }

    // Edit Currency
    public function editCurrency(Request $request, $id)
    {
        // Queries
        $currency_details = Currency::where('id', $id)->where('status', 1)->first();
        $settings = Setting::where('status', 1)->first();
        $config = Config::get();

        if ($currency_details == null) {
            // return redirect()->route('admin.currencies')->with('failed', trans('Currency not found!'));
            return redirect()
                ->route('dahboard.admin.currencies')
                ->with('error', 'Currency not found!');
        } else {
            // return view('admin.pages.currencies.edit', compact('currency_details', 'settings', 'config'));
            return redirect()
                ->route('dahboard.admin.currencies')
                ->with('success', 'Currency not found!');
        }
    }

    // Update Currency
    // public function updateCurrency(Request $request)
    // {
    //     // Validate
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required',
    //         'iso_code' => 'required',
    //         'symbol' => 'required',
    //         'symbol_first' => 'required',
    //     ]);

    //     if ($validator->fails()) {
    //         return back()->with('failed', $validator->messages()->all()[0])->withInput();
    //     }

    //     // Queries
    //     $currency_details = Currency::where('id', $request->id)->first();

    //     if ($currency_details == null) {
    //         return redirect()->route('admin.currencies')->with('failed', trans('Currency not found!'));
    //     } else {
    //         // Update
    //         Currency::where('id', $request->id)->update([
    //             'name' => $request->name,
    //             'iso_code' => $request->iso_code,
    //             'symbol' => $request->symbol,
    //             'symbol_first' => $request->symbol_first,
    //         ]);

    //         // return redirect()->route('admin.currencies')->with('success', trans('Updated!'));

    //         return redirect()
    //             ->route('dahboard.admin.currencies')
    //             ->with('error', 'Currency not found!');
    //     }
    // }


    public function updateCurrency(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:currencies,id',
            'name' => 'required|string',
            'iso_code' => 'required|string',
            'symbol' => 'required|string',
            'symbol_first' => 'required|in:true,false',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $currency = Currency::find($request->id);

        if (!$currency) {
            return redirect()
                ->route('dashboard.admin.currencies')
                ->with('error', __('Currency not found!'));
        }

        $currency->update([
            'name' => $request->name,
            'iso_code' => $request->iso_code,
            'symbol' => $request->symbol,
            'symbol_first' => $request->symbol_first,
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
