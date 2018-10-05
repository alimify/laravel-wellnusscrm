@extends('layouts.admin.app')

@section('title','Products')

@push('css')


@endpush


@section('content')
    <a href="{{route('admin.product.create')}}" class="btn btn-success mt-4 mb-4">Add</a>
    @if($products->count() || $trashes->count())
        <h2>Products</a></h2>
        <a href="{{route('admin.product.index')}}">Active Products ({{$products->count()}})</a>
        - <a href="{{route('admin.product.index',['type' => 'trash'])}}">Trash ({{$trashes->count()}})</a>
        - <a href="{{route('admin.product.restore.all')}}">Restore All</a>

        <div class="table-responsive">
            <table id="example" class="table table-striped table-bordered" style="width:100%">
                <thead>
                <tr>
                    <th>#ID</th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Note</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>

                @foreach((isset($_REQUEST['type']) && $_REQUEST['type'] == 'trash' ? $trashes : $products) as $product)
                    <tr>
                        <td>{{$product->id}}</td>
                        <td>{{$product->code}}</td>
                        <td>{{$product->name}}</td>
                        <td>{{$product->note}}</td>
                        <td><img src="{{asset($product->image)}}"></td>
                        <td>
                            @if(isset($_REQUEST['type']) && $_REQUEST['type'] == 'trash')

                                <a href="{{route('admin.product.restore.single',$product->id)}}" class="restore-item" data-src="{{$product->id}}"><i class="fa fa-undo" aria-hidden="true"></i></a>

                            @else

                                <a href="{{route('admin.product.edit',$product->id)}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                            @endif

                            <a href="javascript:void(0)" class="delete-item" data-src="{{$product->id}}"><i class="fa fa-trash" aria-hidden="true"></i></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-warning text-center">No data available.</div>
    @endif
@endsection


@push('script')
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                destroy: true,
                bDestroy: true,
                "ordering": false,
                "info":     false,
                "lengthChange": false
            });


            $(".delete-item").click(function () {

                let deleteForm = document.createElement('form'),
                    currentURL = `{{route('admin.product.destroy','deleteid')}}`,
                    deleteURL = currentURL.replace('deleteid',this.dataset.src),
                    csrfInput = document.createElement('input'),
                    methodInput = document.createElement('input')
                deleteForm.style.display = 'none';
                deleteForm.method = 'POST'
                deleteForm.action = deleteURL
                csrfInput.name = `_token`
                csrfInput.value = `{{csrf_token()}}`
                methodInput.name = `_method`
                methodInput.value = `DELETE`
                deleteForm.appendChild(csrfInput)
                deleteForm.appendChild(methodInput)
                document.body.appendChild(deleteForm)
                deleteForm.submit()
            })


        } );
    </script>
@endpush
