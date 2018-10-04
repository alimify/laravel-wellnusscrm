<?php

namespace App\Http\Controllers\Caller;

use App\Models\Lead;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LeadController extends Controller
{



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


    public function leadStatus($id,$status){
        $lead = Lead::find($id);
        $lead->status_caller = $status;
        $lead->save();

        return redirect()->back()
            ->with('status','Lead Status Updated..');
    }


}
