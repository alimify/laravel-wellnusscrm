<?php

namespace App\Http\Controllers\Caller;

use App\Models\Lead;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{


    public function index(){

        $leads = Auth::user()->callerTask;
        return response()->view('caller.dashboard',compact('leads'));
    }

    public function indexAjax(Request $request){

        $gleads = Auth::user()->callerTask;
        $leads = [];

        foreach ($gleads as $glead){
            $glead->CallerStatus;
            $leads[] = $glead;
        }

        return response()->json([
            'data' => $leads
        ]);

    }


}
