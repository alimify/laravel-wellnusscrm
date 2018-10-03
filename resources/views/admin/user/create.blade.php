@extends('layouts.admin.app')

@section('title','Create User')

@push('css')


@endpush


@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Add User</h1>
    </div>

    <div class="col-xs-10 col-md-6 mb-5">
    <form action="{{route('admin.user.store')}}" method="post">
        @csrf
        @method('POST')
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" class="form-control" id="name" placeholder="Name">
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" id="email" placeholder="Email">
        </div>

        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" name="phone" class="form-control" id="phone" placeholder="Phone">
        </div>

        <div class="form-group">
            <label for="role">Role</label>
            <select name="role" class="form-control">
                @foreach($roles as $role)
                    <option value="{{$role->id}}">{{$role->title}}</option>
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
