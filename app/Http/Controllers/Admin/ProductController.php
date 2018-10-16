<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    private $directory = 'uploads/products/';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();
        $trashes = Product::onlyTrashed()->get();

      return response()->view('admin.product.index',compact('products','trashes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->view('admin.product.create');
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
          'image' => 'mimes:jpg,png,bmp,gif,jpeg',
          'id'    => 'required|unique:products,id'
      ]);

      $image = $request->file('image');
      $image_name = 'default.png';


      if(is_file($image)){
          $image_name = str_slug($request->name).uniqid().'.'.$image->getClientOriginalExtension();

          if (!Storage::disk('public')->exists($this->directory)) {
              Storage::disk('public')->makeDirectory($this->directory);
          }

          $productImage = Image::make($image)->resize(512,512)->save('tmp/tmp.'.$image->getClientOriginalExtension());
          Storage::disk('public')->put($this->directory.$image_name,$productImage);
      }

      $product = new Product();
      $product->user_id = Auth::check() ? Auth::id() : 0;
      $product->id = $request->id;
      $product->name = $request->name;
      $product->note = $request->note??'';
      $product->status = true;
      $product->image = $this->directory.$image_name;
      $product->save();

      return redirect()->route('admin.product.index')
                        ->with('status','Product Added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);
        $leadData   =     $product->Leads();
        $leads      =     $leadData->get();
        $leadc      =    $leadData->select('leads.id','suppliers.name')
                                  ->join('suppliers','suppliers.id','=','leads.supplier_id')
                                  ->get()
                                  ->groupBy(function ($data){
                                      return $data->name;
                                  })

        ;

        $chart = [];
        $chart[] = ['Suppliers','Leads'];

        foreach ($leadc as $s => $l){
            $chart[] = [$s,count($l)];
        }


        return response()->view('admin.product.show',compact('product','chart','leads'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::find($id);

        return response()->view('admin.product.edit',compact('product'));
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
            'image' => 'mimes:jpg,png,bmp,gif,jpeg',
            'id'    => 'required|unique:products,id,'.$id
        ]);

        $product = Product::find($id);
        $image = $request->file('image');
        $image_name = $product->image;


        if(is_file($image)){
            $image_name = str_slug($request->name).uniqid().'.'.$image->getClientOriginalExtension();

            if (!Storage::disk('public')->exists($this->directory)) {
                Storage::disk('public')->makeDirectory($this->directory);
            }

            if(Storage::disk('public')->exists($product->image)){
                Storage::disk('public')->delete($product->image);
            }

            $productImage = Image::make($image)->resize(512,512)->save('tmp/tmp.'.$image->getClientOriginalExtension());
            Storage::disk('public')->put($this->directory.$image_name,$productImage);
        }

        $product->user_id = Auth::check() ? Auth::id() : 0;
        $product->id = $request->id;
        $product->name = $request->name;
        $product->note = $request->note??'';
        $product->status = true;
        $product->image = $this->directory.$image_name;
        $product->save();

        return redirect()->route('admin.product.index')
            ->with('status','Product Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::withTrashed()->find($id);

        if($product->trashed()){

            if(Storage::disk('public')->exists($product->image)){
                Storage::disk('public')->delete($product->image);
            }

            $product->forceDelete();

        }else{
            $product->delete();
        }

        return redirect()->back()
            ->with('status','User Deleted Successfully');
    }



    public function restoreSingle($id){

        Product::withTrashed()->find($id)
            ->restore();

        return redirect()->back()
            ->with('status','User Deleted Successfully');
    }



    public function restoreSelected($ids){

        Product::withTrashed()->whereIn($ids)
            ->restore();

        return redirect()->back()
            ->with('status','Selected User Successfully Restored');
    }



    public function restoreAll(){

        Product::withTrashed()->restore();

        return redirect()->back()
            ->with('status','User Successfully Restored.');
    }


}
