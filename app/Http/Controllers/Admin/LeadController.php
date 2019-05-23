<?php

namespace App\Http\Controllers\Admin;

use App\Models\Lead;
use App\Models\Status;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LeadController extends Controller
{

    public function index()
    {

        $callers = User::where('role_id',2)->get();
        $statuses = Status::all();

        return response()->view('admin.lead.index',compact('callers','statuses'));
    }





    public function indexAjax(Request $request){

        $fromDate    = $request->fromDate;
        $toDate      = $request->toDate;
        $phone       = $request->phone;
        $order_id    = $request->order_id;
        $name        = $request->name;
        $status      = $request->status;

        $between     = ($fromDate != '' && $toDate != '') || ($fromDate != null && $toDate != null )
                       ? " AND created_at BETWEEN 
                       '".Carbon::createFromFormat('d-m-Y',$fromDate)->toDateTimeString()."'
                       AND '".Carbon::createFromFormat('d-m-Y',$toDate)->toDateTimeString()."'" : '';

        $phone       = $phone != '' || $phone != null ? " AND phone = '".$phone."'": '';
        $order_id    = $order_id != '' || $order_id != null ? " AND phone = '".$order_id."'": '';
        $name        = $name != '' || $name != null ? " AND name = '".$name."'": '';
        $status       = $status != '' || $status != null ? " AND status_admin = $status": '';

        $sql         = "$between$phone$order_id$name$status";

        $sql         = preg_replace('/AND/', '', $sql, 1);


        $data = [];



            $cleads        = Lead::whereRaw($sql)->orderBy('created_at','desc')
                                                 ->get();

            foreach ($cleads as $lead){
                $lead->Supplier;
                $lead->Product;
                $lead->AdminStatus;
                $lead->CallerStatus;
                $data[]  = $lead;
            }


        $requesd = [
            'fromDate' => $request->fromDate,
            'toDate' => $request->toDate,
            'name' => $request->name,
            'phone' => $request->phone,
            'order_id' => $request->order_id,
        ];

        return response()->json([
            'request' => $requesd ,
            'data'    => $data,
        ]);

    }






    public function create()
    {
        //
    }




    public function store(Request $request)
    {
        //
    }




    public function show($id)
    {
        //
    }




    public function edit($id)
    {
        //
    }





    public function update(Request $request, $id)
    {
        $lead = Lead::find($id);
        $lead->name = $request->name;
        $lead->phone = $request->phone;
        $lead->email = $request->email;
        $lead->address = $request->address;
        $lead->status_caller = $request->caller_status;
        $lead->save();
        $lead->AdminStatus;
        $lead->CallerStatus;

        return response()->json([
            'status' => true,
            'id'  => $id,
            'lead' => $lead
        ]);
    }





    public function destroy($id)
    {/*
        $lead = Lead::withTrashed()->find($id);

        if($lead->trashed()){
            $lead->forceDelete();
        }else{
            $lead->delete();
        }

        return response()->json([
            'status' => true,
            'id' => $id
        ]);*/
    }




    public function leadStatus($id,$status){
        $lead = Lead::find($id);
        $lead->status_admin = $status;
        $lead->save();
        $lead->AdminStatus;
        $lead->CallerStatus;

        return response()->json([
            'status' => true,
            'lead_id' =>  $id,
            'lead_status' => $status,
            'lead' => $lead
        ]);
    }


    public function editNote(Request $request){

        if(!isset($request->id) || $request->id == null){
            return response()->json([
                'status' => false,
                'id'     => $request->id,
                'note'   => $request->note,
                'lead'   => ''
            ]);
        }

        $lead = Lead::find($request->id);
        $lead->note = $request->note;
        $lead->save();
        $lead->AdminStatus;
        $lead->CallerStatus;

        return response()->json([
            'status' => true,
            'id' => $request->id,
            'note' => $request->note,
            'lead' => $lead
        ]);
    }


    public function sameLead(Request $request){
        $leads = Lead::where('phone',$request->phone)
                       ->where('product_id',$request->product)->get();

        return response()->json([
            'leads'  => $leads
        ]);
    }


    public function sendTask(Request $request){
        $tasks = preg_replace("/\[([^\[\]]++|(?R))*+\]/", "", $request->task);
        $tasks = explode(',',str_replace('"','',$tasks));
        if((!isset($request->callerId) ||$request->callerId == null || $request->callerId == '')
        || (!isset($request->task) ||$request->task == null || $request->task == '' || !count($tasks))){
            return response()->json([
                'status' => false
            ]);
        }


        Lead::whereIn('id',$tasks)->update([

            'caller_id' => $request->callerId,
            'update_admin' => Carbon::now()->toDateTimeString()

        ]);

        return response()->json([
            'status' => true,
            'callerId' => $request->callerId,
            'task' => $request->task
        ]);
    }


}
