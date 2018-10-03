@extends('layouts.admin.app')

@section('title','Edit User')

@push('css')


@endpush


@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center mb-3 pt-3 pb-2 border-bottom">
        <h1 class="h2">Create User</h1>
    </div>

    <div class="col-xs-10 col-md-6 mb-5">
        <form action="{{route('admin.user.update',$user->id)}}" method="post">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" class="form-control" id="name" value="{{$user->name}}">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" id="email" value="{{$user->email}}">
            </div>

            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" name="phone" class="form-control" id="phone" value="{{$user->Userextra->phone}}">
            </div>

            <div class="form-group">
                <label for="role">Role</label>
                <select name="role" class="form-control">
                    @foreach($roles as $role)
                        <option value="{{$role->id}}" {{$user->role_id == $role->id ? 'selected' : ''}}>{{$role->title}}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" id="password">
            </div>

            <div class="form-group">
                <label for="password">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" id="password">
            </div>


            <a class="btn btn-danger" href="{{route('admin.user.index')}}">Back</a> <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection


@push('script')

@endpush
