<?php

namespace App\Http\Controllers\Admin;

use App\Models\Lead;
use App\Models\Status;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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

        $cleads        = Lead::whereRaw($sql)->orderBy('created_at','desc')
                                            ->get();

        $ctrashes      = Lead::whereRaw($sql)->onlyTrashed()
                                            ->orderBy('created_at','desc')
                                            ->get();


        $data = [];



        if(isset($request->type) && $request->type == 'trash') {
            foreach ($ctrashes as $trash) {
                $trash->Supplier;
                $trash->AdminStatus;
                $trash->CallerStatus;
                $data[] = $trash;
            }

        }else{
            foreach ($cleads as $lead){
                $lead->Supplier;
                $lead->AdminStatus;
                $lead->CallerStatus;
                $data[]  = $lead;
            }
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $lead = Lead::find($id);
        $lead->name = $request->name;
        $lead->phone = $request->phone;
        $lead->email = $request->email;
        $lead->address = $request->address;
        $lead->status_caller = $request->caller_status;
        $lead->save();

        return response()->json([
            'status' => true,
            'id'  => $id
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function destroy($id)
    {
        $lead = Lead::withTrashed()->find($id);

        if($lead->trashed()){
            $lead->forceDelete();
        }else{
            $lead->delete();
        }

        return response()->json([
            'status' => true,
            'id' => $id
        ]);
    }



    public function restoreSingle($id){

        Lead::withTrashed()->find($id)
            ->restore();

        return response()->json([
            'status' => true,
            'id' => $id
        ]);
    }



    public function restoreSelected($ids){

        Lead::withTrashed()->whereIn($ids)
            ->restore();

        return redirect()->back()
            ->with('status','Selected User Successfully Restored');
    }



    public function restoreAll(){

        Lead::withTrashed()->restore();

        return redirect()->back()
            ->with('status','User Successfully Restored.');
    }



    public function leadStatus($id,$status){
        $lead = Lead::find($id);
        $lead->status_admin = $status;
        $lead->save();

        return response()->json([
            'status' => true,
            'lead_id' =>  $id,
            'lead_status' => $status
        ]);
    }

    public function editNote(Request $request){

        if(!isset($request->id) || $request->id == null){
            return response()->json([
                'status' => false,
                'id'     => $request->id,
                'note'   => $request->note
            ]);
        }

        $lead = Lead::find($request->id);
        $lead->note = $request->note;
        $lead->save();

        return response()->json([
            'status' => true,
            'id' => $request->id,
            'note' => $request->note
        ]);
    }


    public function sendTask(Request $request){

        if((!isset($request->callerId) ||$request->callerId == null || $request->callerId == '')
        || (!isset($request->task) ||$request->task == null || $request->task == '' || !count($request->task))){
            return response()->json([
                'status' => false
            ]);
        }
        $tasks = explode(',',str_replace('"','',$request->task));


        Lead::whereIn('id',$tasks)->update(['caller_id' => $request->callerId]);

        return response()->json([
            'status' => true,
            'callerId' => $request->callerId,
            'task' => $request->task
        ]);
    }
}
