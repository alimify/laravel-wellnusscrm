<?php

namespace App\Http\Controllers\Api;

use App\Models\Lead;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    public function Order(Request $request){


        $error = false;

        switch ($request){
            case (!isset($request->supplier_id) || $request->supplier_id == '') :
                $error = true;
                $status = false;
                $code = 1;
                $message = 'Supplier ID Must required';
                break;
            case (!isset($request->api_key)   || $request->api_key == '') :
                $error = true;
                $status = false;
                $code = 2;
                $message = 'API Key must required';
                 break;
            case (!isset($request->product_id) || $request->product_id == '') :
                $error = true;
                $status = false;
                $code = 3;
                $message = 'Product ID Must required';
                break;

            case (!isset($request->name) || $request->name == '') :
                $error = true;
                $status = false;
                $code = 4;
                $message = 'Name Must required';
                break;

            case (!isset($request->phone) || $request->phone == '') :
                $error = true;
                $status = false;
                $code = 5;
                $message = 'Phone Must required';
                break;
        }

        if($error){
            return response()->json([
                'status'                   => $status,
                'code'                     => $code,
                'message'                  => $message
            ]);
        }

        $supplier = Supplier::where('id','=',$request->supplier_id)
                              ->first();

        if(!isset($supplier->id)){

            return response()->json([
                'status'                   => false,
                'code'                     => 6,
                'message'                  => 'Supplier not found'
            ]);
        }

        if($supplier->api != $request->api_key){


            return response()->json([
                'status'                   => false,
                'code'                     => 7,
                'message'                  => 'API Key do not match'
            ]);


        }

        $product = Product::where('id',$request->product_id)->first();

        if(!isset($product->id)){

            return response()->json([

                'status'                   => false,
                'code'                     => 8,
                'message'                  => 'Product not found'

            ]);

        }


        $supplier_serial = Lead::where('supplier_id',$request->supplier_id)
                                 ->orderBy('supplier_serial','desc')
                                 ->first();


        $product_serial = Lead::where('product_id',$request->product_id)
                                ->orderBy('product_serial','desc')
                                ->first();

        $lead = new Lead();
        $lead->product_id                   = $request->product_id;
        $lead->supplier_id                  = $request->supplier_id;
        $lead->name                         = $request->name;
        $lead->phone                        = $request->phone;
        $lead->email                        = $request->email;
        $lead->address                      = $request->address;
        $lead->order_id                     = $request->order_id;
        $lead->publisher_id                 = $request->publisher_id;
        $lead->supplier_serial              = ($supplier_serial->supplier_serial??0)+1;
        $lead->product_serial               = ($product_serial->product_serial??0)+1;
        $lead->note                         = '';
        $lead->updated_at                   = null;
        $lead->update_caller                = null;
        $lead->update_admin                 = null;
        $lead->save();

        $data = [
            'product_id'                    => $request->product_id,
            'supplier_id'                   => $request->supplier_id,
            'name'                          => $request->name,
            'phone'                         => $request->phone,
            'email'                         => $request->email,
            'address'                       => $request->address,
            'order_id'                      => $request->order_id,
            'publisher_id'                  => $request->publisher_id,
            'supplier_serial'               => $lead->supplier_serial,
            'product_serial'                => $lead->product_serial
        ];

        return response()->json([

            'status'                        => true,
            'code'                          => 200,
            'message'                       => 'Order successfully placed.',
            'request'                       => $data

        ]);
    }
}
