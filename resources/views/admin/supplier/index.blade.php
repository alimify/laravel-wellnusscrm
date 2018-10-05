@extends('layouts.admin.app')

@section('title','Suppliers')

@push('css')


@endpush


@section('content')
    <a href="{{route('admin.supplier.create')}}" class="btn btn-success mt-4 mb-4">Add</a>
    @if($suppliers->count() || $trashes->count())
        <h2>Suppliers</a></h2>
        <a href="{{route('admin.supplier.index')}}">Active Suppliers ({{$suppliers->count()}})</a>
        - <a href="{{route('admin.supplier.index',['type' => 'trash'])}}">Trash ({{$trashes->count()}})</a>
        - <a href="{{route('admin.supplier.restore.all')}}">Restore All</a>

        <div class="table-responsive">
            <table id="example" class="table table-striped table-bordered" style="width:100%">
                <thead>
                <tr>
                    <th>#ID</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Note</th>
                    <th>API Info</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>

                @foreach((isset($_REQUEST['type']) && $_REQUEST['type'] == 'trash' ? $trashes : $suppliers) as $supplier)
                    <tr>
                        <td>{{$supplier->id}}</td>
                        <td>{{$supplier->name}}</td>
                        <td>{{$supplier->phone}}</td>
                        <td>{{$supplier->email}}</td>
                        <td>{{$supplier->address}}</td>
                        <td>{{$supplier->note}}</td>
                        <td class="text-center"><a href="javascript:void(0)" data-id="{{$supplier->id}}" data-key="{{$supplier->api}}" data-toggle="modal" data-target="#apiModal" id="apiInfo"><i class="fa fa-eye"></i></a></td>
                        <td>
                            @if(isset($_REQUEST['type']) && $_REQUEST['type'] == 'trash')

                                <a href="{{route('admin.supplier.restore.single',$supplier->id)}}" class="restore-item" data-src="{{$supplier->id}}"><i class="fa fa-undo" aria-hidden="true"></i></a>

                            @else

                                <a href="{{route('admin.supplier.edit',$supplier->id)}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                            @endif

                            <a href="javascript:void(0)" class="delete-item" data-src="{{$supplier->id}}"><i class="fa fa-trash" aria-hidden="true"></i></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-warning text-center">No data available.</div>
    @endif


    <!-- Modal -->
    <div class="modal fade" id="apiModal" tabindex="-1" role="dialog" aria-labelledby="apiModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="noteModalLabel">API Info</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="modal-supplier-id" class="row border-bottom mb-2 p-2"></div>
                        <div id="modal-api-key" class="row border-bottom mb-2 p-2"></div>
                        <div class="p-5">
                            <div class="row border mb-2 p-2">
                                <b>URL :</b> <span class="ml-3">{{route('api.order')}}</span>
                            </div>
                            <div class="row border mb-2 p-2">
                                <b>Request Type :</b>  <span class="ml-3">GET / POST</span>
                            </div>
                            <div class="row border mb-2 p-2">
                               <b> Parameters : </b> <span class="ml-3">product_id*, supplier_id*, api_key*, name*, phone*, email, address, order_id, publisher_id
                            </span>
                            </div>

                            <div class="row border mb-2 p-2">
                                <b>Note :</b>  <span class="ml-3">* parameters are must required.</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
            </div>
        </div>
    </div>

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
                    currentURL = `{{route('admin.supplier.destroy','deleteid')}}`,
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

            $("#apiInfo").click(function () {
                $("#modal-supplier-id").html(`<b>Supplier ID :</b><span class="ml-3">`+this.dataset.id+`</span>`)
                $("#modal-api-key").html(`<b>API Key :<b><span class="ml-3">`+this.dataset.key+`</span>`)
            })

        } );
    </script>
@endpush
