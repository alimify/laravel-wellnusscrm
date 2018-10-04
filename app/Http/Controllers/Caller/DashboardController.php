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
        $tasks = Task::where('user_id',Auth::id())->pluck('lead_id')
                                                  ->toArray();
        $leads = Lead::whereIn('id',$tasks)->get();

        return response()->view('caller.dashboard',compact('leads'));
    }


}
