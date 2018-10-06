<?php

namespace App\Http\Controllers\Admin;

use App\Models\Lead;
use Carbon\Carbon;
use function foo\func;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{


    public function index(){
        $today = Carbon::today();
        $data = Lead::select('id','created_at')->where('created_at','>=',$today->subDays(7))
                                                ->get()
                                                ->groupBy(function ($day){
                                                   return Carbon::parse($day->created_at)->format('l');
                                                })
        ;

        $leads = [];
        $labels = [];
        $days   = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];


        for($i = 0; $i <= 6; $i++) {
            $dayofWeek = $days[$i];
            $labels[]  = $dayofWeek;
            $leads[]   = count($data[$dayofWeek]??[]);
        }

        return response()->view('admin.dashboard',compact('leads','labels'));
    }
}
