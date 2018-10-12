@extends('layouts.admin.app')

@section('title','Edit Product')

@push('css')


@endpush


@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Add Product</h1>
    </div>

    <div class="col-xs-10 col-md-6 mb-5">
        <form action="{{route('admin.product.update',$product->id)}}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="id">ID</label>
                <input type="text" name="id" class="form-control" id="id" value="{{$product->id}}">
            </div>

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" class="form-control" id="name" value="{{$product->name}}">
            </div>
            <div class="form-group">
                <label for="email">Note</label>
                <textarea class="form-control" name="note">{{$product->note}}</textarea>
            </div>

            <div class="form-group">
                <label for="phone">Image</label>
                <img src="{{asset($product->image)}}">
                <input type="file" name="image" class="form-control-file" id="image">
            </div>

            <!--<div class="form-group">
                <label for="phone" class="form-label form-control-file">Status</label>
                <input type="checkbox" name="status" class="form-check-inline" id="status" value="1"> <label for="status" class="form-check-label">Active</label>
            </div>-->
            <a class="btn btn-danger" href="{{route('admin.user.index')}}">Back</a> <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection


@push('script')

@endpush
