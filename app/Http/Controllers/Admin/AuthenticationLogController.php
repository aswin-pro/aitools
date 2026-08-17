<?php

namespace App\Http\Controllers\Admin;

use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Inertia\Inertia;

class AuthenticationLogController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */



public function index(Request $request)
{
    $perPage = $request->integer('per_page', 10); //getting per page count from req def 10 row 
    $search = $request->input('search');

    $query = DB::table('authentication_log')
        ->orderBy('id', 'desc');

    if ($search) {
        $query->where(function ($query) use ($search) {
            $query->where('ip_address', 'like', "%{$search}%")
                ->orWhere('user_agent', 'like', "%{$search}%")
                ->orWhere('login_at', 'like', "%{$search}%")
                ->orWhere('logout_at', 'like', "%{$search}%");
        });
    }

    $logs = $query
        ->paginate($perPage)
        ->withQueryString();

    $logs->getCollection()->transform(function ($log) {
        $location = json_decode($log->location);

        if (empty($location)) {
            $log->state_name = 'Connecticut';
            $log->city = 'New Haven';
            $log->country = 'United States';
            $log->postal_code = '06510';
        } else {
            $log->state_name = $location->state_name ?? '';
            $log->city = $location->city ?? '';
            $log->country = $location->country ?? '';
            $log->postal_code = $location->postal_code ?? '';
        }

        $log->location = urldecode(
            $log->state_name . ', ' .
            $log->city . ', ' .
            $log->country . ', ' .
            $log->postal_code
        );

        $agent = new Agent();
        $agent->setUserAgent($log->user_agent);

        $log->platform = $agent->platform();
        $log->browser = $agent->browser();

        return $log;
    });

    return Inertia::render(
        'admin/system/login-activity/index',
        [
            'logs' => $logs,
        ]
    );
}   


    // My account
    // public function index()
    // {
    //     // Queries
    //     $logs = DB::table('authentication_log')->orderBy('id', 'desc')->get();

    //     // Loop
    //     for ($i = 0; $i < count($logs); $i++) {

    //         // State, City, Country & Postal Code
    //         if (json_decode($logs[$i]->location) == "") {
    //             $logs[$i]->state_name = 'Connecticut';
    //             $logs[$i]->city = 'New Haven';
    //             $logs[$i]->country = 'United States';
    //             $logs[$i]->postal_code = '06510';
    //         } else {
    //             $logs[$i]->state_name = json_decode($logs[$i]->location)->state_name;
    //             $logs[$i]->city = json_decode($logs[$i]->location)->city;
    //             $logs[$i]->country = json_decode($logs[$i]->location)->country;
    //             $logs[$i]->postal_code = json_decode($logs[$i]->location)->postal_code;
    //         }

    //         // Concatinate
    //         $logs[$i]->location = urldecode($logs[$i]->state_name . ', ' . $logs[$i]->city . ', ' . $logs[$i]->country . ', ' . $logs[$i]->postal_code);

    //         // Get User Agent
    //         $agent = new Agent();
    //         $agent->setUserAgent($logs[$i]->user_agent);

    //         // Push variables
    //         $logs[$i]->platform = $agent->platform();
    //         $logs[$i]->browser = $agent->browser();
    //     }

    //     // return view('admin.pages.logs.index', compact('logs'));

    //     return Inertia::render('admin/system/login-activity/index', compact('logs'));
    // }
}
