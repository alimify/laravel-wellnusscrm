<?php

namespace App\Http\Controllers\Caller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{


    public function index(){
        return response()->view('caller.dashboard');
    }


}
