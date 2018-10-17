<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Product;
use App\Models\Status;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{


    public function index(){
        $suppliers = Supplier::all();
        $products  = Product::all();
        $statuses = Status::all();

        return response()->view('admin.dashboard',compact('suppliers','products','statuses'));
    }


    public function indexAjax(Request $request){

        $fromDate       = $request->fromDate;
        $toDate         = $request->toDate;
        $supplier       = $request->supplier;
        $product        = $request->product;
        $status         = $request->status;

        $between        = ($fromDate != '' && $toDate != '') || ($fromDate != null && $toDate != null )
            ? " AND created_at BETWEEN 
                       '".Carbon::createFromFormat('d-m-Y',$fromDate)->toDateTimeString()."'
                       AND '".Carbon::createFromFormat('d-m-Y',$toDate)->toDateTimeString()."'" : '';

        $supplier       = $supplier != '' || $supplier != null ? " AND supplier_id = '".$supplier."'": '';
        $product        = $product != '' || $product != null ? " AND product_id = '".$product."'": '';
        $status         = $status != '' || $status != null ? " AND status_admin = $status": '';

        $sql            = "$between$supplier$product$status";

        $sql            = preg_replace('/AND/', '', $sql, 1);

        if($sql) {
            $leads = Lead::selectRaw("status_admin")
                          ->whereRaw($sql)
                          ->get()
                          ->groupBy(function ($lead) {
                                   return $lead->status_admin;
                          });
        }else{
            $leads = Lead::selectRaw("status_admin")
                          ->get()
                          ->groupBy(function ($lead) {
                           return $lead->status_admin;
                          });
        }


        for ($i = 1; $i <= 4; $i++ ){
            $leads[$i] = isset($leads[$i]) ? count($leads[$i]) : 0;
        }

        return response()->json($leads);
    }


}
