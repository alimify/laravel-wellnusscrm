<?php

namespace App\Http\Controllers\Caller;

use App\Models\Lead;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{


    public function index(){
        return response()->view('caller.dashboard');
    }

    public function indexAjax(Request $request){

        if(isset($request->status)){

            $gleads = Lead::where(function ($query){

                return $query->where('caller_id',Auth::id())
                              ->orWhereNotNull('update_caller');

                          })->where('status_caller',$request->status)
                         ->whereBetween('created_at',[Carbon::createFromFormat('d-m-Y',$request->fromDate)->toDateTimeString(),Carbon::createFromFormat('d-m-Y',$request->toDate)->toDateTimeString()])
                         ->get();

        }else {
            $gleads = Auth::user()->callerTask()
                                  ->whereBetween('created_at',[$request->fromDate,$request->toDate])
                                  ->get()
            ;
        }
        $leads = [];

        foreach ($gleads as $glead){
            $glead->CallerStatus;
            $glead->Product;
            $leads[] = $glead;
        }

        return response()->json([
            'data' => $leads
        ]);

    }


}
