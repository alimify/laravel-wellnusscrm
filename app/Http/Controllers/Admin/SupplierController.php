<?php

namespace App\Http\Controllers\Admin;

use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $suppliers = Supplier::all();
        $trashes = Supplier::onlyTrashed()->get();

        return response()->view('admin.supplier.index',compact('suppliers','trashes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->view('admin.supplier.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|max:100',
            'phone' => 'max:16',
            'email' => 'email|max:150',
            'address' => 'max:200',
            'note' => 'max:500',
            'api' => 'required'
        ]);


        $supplier = new Supplier();
        $supplier->user_id = Auth::check() ? Auth::id() : 0;
        $supplier->name = $request->name;
        $supplier->api = $request->api;
        $supplier->phone = $request->phone;
        $supplier->email = $request->email;
        $supplier->address = $request->address;
        $supplier->note = $request->note;
        $supplier->status = true;
        $supplier->save();

        return redirect()->route('admin.supplier.index')
                          ->with('status','Supplier Added Successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $supplier = Supplier::find($id);
        $leadData = $supplier->Leads();
        $leads    = $leadData->get();

        $leadc      =    $leadData->select('leads.id','products.name')
                                  ->join('products','products.id','=','leads.product_id')
                                  ->get()
                                  ->groupBy(function ($data){
                                      return $data->name;
                                  })

        ;

        $chart = [];
        $chart[] = ['Products','Leads'];

        foreach ($leadc as $s => $l){
            $chart[] = [$s,count($l)];
        }

        return response()->view('admin.supplier.show',compact('supplier','chart','leads'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $supplier = Supplier::find($id);

        return response()->view('admin.supplier.edit',compact('supplier'));
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
        $this->validate($request,[
            'name' => 'required|max:100',
            'phone' => 'max:16',
            'email' => 'email|max:150',
            'address' => 'max:200',
            'note' => 'max:500'
        ]);


        $supplier = Supplier::find($id);
        $supplier->user_id = Auth::check() ? Auth::id() : 0;
        $supplier->name = $request->name;
        $supplier->phone = $request->phone;
        $supplier->email = $request->email;
        $supplier->address = $request->address;
        $supplier->note = $request->note;
        $supplier->status = true;
        $supplier->save();

        return redirect()->back()
            ->with('status','Supplier Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $supplier = Supplier::withTrashed()->find($id);

        if($supplier->trashed()){
            $supplier->forceDelete();
        }else{
            $supplier->delete();
        }

        return redirect()->back()
            ->with('status','User Deleted Successfully');
    }



    public function restoreSingle($id){

        Supplier::withTrashed()->find($id)
            ->restore();

        return redirect()->back()
            ->with('status','User Deleted Successfully');
    }



    public function restoreSelected($ids){

        Supplier::withTrashed()->whereIn($ids)
            ->restore();

        return redirect()->back()
            ->with('status','Selected User Successfully Restored');
    }



    public function restoreAll(){

        Supplier::withTrashed()->restore();

        return redirect()->back()
            ->with('status','User Successfully Restored.');
    }

}
