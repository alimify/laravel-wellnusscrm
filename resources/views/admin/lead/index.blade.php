@extends('layouts.admin.app')

@section('title','Leads')

@push('css')


@endpush


@section('content')

    @if($leads->count() || $trashes->count())
        <h2>Leads</a></h2>
        <a href="{{route('admin.lead.index')}}">Leads ({{$leads->count()}})</a>
        - <a href="{{route('admin.lead.index',['type' => 'trash'])}}">Trash ({{$trashes->count()}})</a>
        - <a href="{{route('admin.lead.restore.all')}}">Restore All</a>
<form method="POST" action="{{route('admin.lead.sendTask')}}">
    @csrf
    @method("POST")
        <div class="table-responsive">
            <table id="example" class="table table-striped table-bordered" style="width:100%">
                <thead>
                <tr>
                    <th></th>
                    <th>#</th>
                    <th>Product ID</th>
                    <th>Product Code</th>
                    <th>DateTime</th>
                    <th>Supplier</th>
                    <th>Customer</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Order Id</th>
                    <th>Status Admin</th>
                    <th>Status Caller</th>
                    <th>Note</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>

                @foreach((isset($_REQUEST['type']) && $_REQUEST['type'] == 'trash' ? $trashes : $leads) as $lead)
                    <tr>
                        <td><input type="checkbox" name="task[]" value="{{$lead->id}}"></td>
                        <td>{{$lead->id}}</td>
                        <td>{{$lead->product_id}}</td>
                        <td>{{$lead->Product->code}}</td>
                        <td>{{$lead->created_at}}</td>
                        <td>{{$lead->Supplier->name}}</td>
                        <td>{{$lead->name}}</td>
                        <td>{{$lead->phone}}</td>
                        <td>{{$lead->email}}</td>
                        <td>{{$lead->address}}</td>
                        <td>{{$lead->order_id}}</td>
                        <td><span class="{{$lead->AdminStatus->class}}">{{$lead->AdminStatus->title}}</span></td>
                        <td><span class="{{$lead->CallerStatus->class}}">{{$lead->CallerStatus->title}}</span></td>
                        <td>{{$lead->note}}<br/><a href="javascript:void(0)" data-src="{{$lead->id}}" data-content="{{$lead->note}}" class="text-center note-modal" data-toggle="modal" data-target="#noteModal"><i class="fa fa-plus"></i></a></td>
                        <td>
                            @if(isset($_REQUEST['type']) && $_REQUEST['type'] == 'trash')

                                <a href="{{route('admin.lead.restore.single',$lead->id)}}" class="restore-item" data-src="{{$lead->id}}"><i class="fa fa-undo" aria-hidden="true"></i></a>

                            @else

                                <a href="{{ route('admin.lead.status',['id' => $lead->id, 'status' => 1]) }}"><i class="fa fa-check" aria-hidden="true"></i></a>
                                <a href="{{ route('admin.lead.status',['id' => $lead->id, 'status' => 2]) }}"><i class="fa fa-times-circle" aria-hidden="true"></i></a>
                                <a href="{{ route('admin.lead.status',['id' => $lead->id, 'status' => 3]) }}"><i class="fa fa-pause" aria-hidden="true"></i></a>

                            @endif

                            <a href="javascript:void(0)" class="delete-item" data-src="{{$lead->id}}"><i class="fa fa-trash" aria-hidden="true"></i></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    <div class="justify-content-center form-inline">
    <select name="callerId" class="form-control col-md-3 col-sm-8 form-inline">
        @foreach($callers as $caller)
            <option value="{{$caller->id}}">{{$caller->name}}</option>
            @endforeach
    </select>
    <input type="submit" name="submit" value="Submit" class="btn btn-primary btn-success form-inline">
    </div>
</form>
    @else
        <div class="text-warning text-center">No data available.</div>
    @endif

    <!-- Modal -->
    <div class="modal fade" id="noteModal" tabindex="-1" role="dialog" aria-labelledby="noteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{route('admin.lead.note.edit')}}" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="noteModalLabel">Note</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                        @csrf
                        @method('POST')
                        <input id="modal-note-id" type="hidden" name="id">
                       <textarea class="form-control" id="modal-note" name="note"></textarea>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="submit" class="btn btn-primary">Save changes</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@push('script')
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                "ordering": false,
                "info":     false,
                "lengthChange": false
            });


            $(".delete-item").click(function () {

                let deleteForm = document.createElement('form'),
                    currentURL = `{{route('admin.lead.destroy','deleteid')}}`,
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

            $(".note-modal").click(function () {
                $("#modal-note-id").val(this.dataset.src)
                $("#modal-note").text(this.dataset.content)
            })



        } );
    </script>
@endpush
