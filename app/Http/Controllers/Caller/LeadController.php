<?php

namespace App\Http\Controllers\Caller;

use App\Models\Lead;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LeadController extends Controller
{



    public function editNote(Request $request){

        if(!isset($request->id) || $request->id == null || $request->id == ''){

            return response()->json([
                'status' => false
            ]);
        }

        $lead = Lead::find($request->id);
        $lead->note = $request->note;
        $lead->save();
        $lead->CallerStatus;
        $lead->Product;
        return response()->json([
            'status' => true,
            'id' => $lead->id,
            'note' => $lead->note,
            'lead' => $lead
        ]);
    }

    public function editAddress(Request $request){

        if(!isset($request->id) || $request->id == null || $request->id == ''){

            return response()->json([
                'status' => false
            ]);
        }

        $lead = Lead::find($request->id);
        $lead->address = $request->address;
        $lead->save();
        $lead->CallerStatus;
        $lead->Product;
        return response()->json([
            'status' => true,
            'id' => $lead->id,
            'address' => $lead->address,
            'lead'   => $lead
        ]);
    }



    public function leadStatus($id,$status){
        $lead = Lead::find($id);
        $lead->status_caller = $status;
        $lead->save();
        $lead->CallerStatus;
        $lead->Product;
        return response()->json([
            'status' => true,
            'id' => $lead->id,
            'lead' => $lead
        ]);
    }


}
