<?php

namespace App\Http\Controllers\Admin;

use App\Models\Lead;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leads = Lead::all();
        $trashes = Lead::onlyTrashed()->get();

        return response()->view('admin.lead.index',compact('leads','trashes'));
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
        //
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

        return redirect()->back()
            ->with('status','User Deleted Successfully');
    }



    public function restoreSingle($id){

        Lead::withTrashed()->find($id)
            ->restore();

        return redirect()->back()
            ->with('status','User Deleted Successfully');
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

        return redirect()->back()
                          ->with('status','Lead Status Updated..');
    }

    public function editNote(Request $request){
        $this->validate($request,[
          'id' => 'required'
        ]);

        $lead = Lead::find($request->id);
        $lead->note = $request->note;
        $lead->save();

        return redirect()->back()
                          ->with('status','Lead Note Updated..');
    }
}
