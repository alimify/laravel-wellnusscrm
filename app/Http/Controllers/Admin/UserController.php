<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\Userextra;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $users = User::all();
        $trashes = User::onlyTrashed()->get();

       return response()->view('admin.user.index',compact('users','trashes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();

        return response()->view('admin.user.create',compact('roles'));
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
            'name'     => 'required|min:5|max:15',
            'email'    => 'required',
            'phone'    => 'required',
            'role'     => 'required',
            'password' => 'required|string|min:6|confirmed'
        ]);


        $phone = new Userextra();
        $phone->phone = $request->phone;
        $phone->save();


        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        $user->Userextra()->associate($phone);
        $user->Role()->associate($request->role);

        $user->save();
        return redirect()->route('admin.user.index')->with('status','User Successfully Created');
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
        $user = User::find($id);
        $roles = Role::all();

        return response()->view('admin.user.edit',compact('user','roles'));
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
            'name'     => 'required|min:5|max:15',
            'email'    => 'required',
            'phone'    => 'required',
            'role'     => 'required'
        ]);


        if($request->password){
            $this->validate($request,[
                'password' => 'required|string|min:6|confirmed'
            ]);
        }

        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;

        if($request->password){
            $user->password = Hash::make($request->password);
        }


        $user->Role()->associate($request->role);

        $phone = Userextra::find($user->userextra_id);
        $phone->phone = $request->phone;
        $phone->save();

        $user->save();
        return redirect()->back()->with('status','User Successfully Created');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::withTrashed()->find($id);

        if($user->trashed()){
            $user->forceDelete();
        }else{
            $user->delete();
        }

        return redirect()->back()
                          ->with('status','User Deleted Successfully');
    }



    public function restoreSingle($id){

        User::withTrashed()->find($id)
                           ->restore();

        return redirect()->back()
                          ->with('status','User Deleted Successfully');
    }



    public function restoreSelected($ids){

        User::withTrashed()->whereIn($ids)
                           ->restore();

        return redirect()->back()
                          ->with('status','Selected User Successfully Restored');
    }



    public function restoreAll(){

        User::withTrashed()->restore();

        return redirect()->back()
                          ->with('status','User Successfully Restored.');
    }


}
