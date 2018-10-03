@extends('layouts.admin.app')

@section('title','Edit Supplier')

@push('css')


@endpush


@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Add Supplier</h1>
    </div>

    <div class="col-xs-10 col-md-6 mb-5">
        <form action="{{route('admin.supplier.update',$supplier->id)}}" method="post">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="phone">API</label>
                <input type="text" name="api" class="form-control" id="api" value="{{$supplier->api}}" disabled>
            </div>

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" class="form-control" id="name" value="{{$supplier->name}}">
            </div>

            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" name="phone" class="form-control" id="phone" value="{{$supplier->phone}}">
            </div>

            <div class="form-group">
                <label for="name">Email</label>
                <input type="email" name="email" class="form-control" id="email" value="{{$supplier->email}}">
            </div>

            <div class="form-group">
                <label for="name">Address</label>
                <textarea name="address" class="form-control" rows="1">{{$supplier->address}}</textarea>
            </div>

            <div class="form-group">
                <label for="email">Note</label>
                <textarea class="form-control" name="note">{{$supplier->note}}</textarea>
            </div>

            <!--<div class="form-group">
                <label for="phone" class="form-label form-control-file">Status</label>
                <input type="checkbox" name="status" class="form-check-inline" id="status" value="1"> <label for="status" class="form-check-label">Active</label>
            </div>-->
            <a class="btn btn-danger" href="{{route('admin.supplier.index')}}">Back</a> <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection


@push('script')

@endpush
