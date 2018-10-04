<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- FAVICON Icon -->
    <link rel="icon" href="../../../../favicon.ico">

    <!-- Title -->
    <title>Leads Management</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{asset('css/admin/dashboard.css')}}" rel="stylesheet">

</head>

<body>
@include('layouts.admin.partials.topbar')

<div class="container-fluid justify-content-center mt-3">
    <div class="row">


        <main role="main" class="col-12 px-4">
            @include('layouts.admin.partials.notice')

            @if($leads->count())
                <h2>Leads ({{$leads->count()}})</a></h2>

                    <div class="table-responsive">
                        <table id="example" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Product ID</th>
                                <th>Product Code</th>
                                <th>DateTime</th>
                                <th>Customer</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th>Order Id</th>
                                <th>Status</th>
                                <th>Note</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($leads as $lead)
                                <tr>
                                    <td>{{$lead->id}}</td>
                                    <td>{{$lead->product_id}}</td>
                                    <td>{{$lead->Product->code}}</td>
                                    <td>{{$lead->created_at}}</td>
                                    <td>{{$lead->name}}</td>
                                    <td>{{$lead->phone}}</td>
                                    <td>{{$lead->email}}</td>
                                    <td>{{$lead->address}}</td>
                                    <td>{{$lead->order_id}}</td>
                                    <td><span class="{{$lead->CallerStatus->class}}">{{$lead->CallerStatus->title}}</span></td>
                                    <td>{{$lead->note}}<br/><a href="javascript:void(0)" data-src="{{$lead->id}}" data-content="{{$lead->note}}" class="text-center note-modal" data-toggle="modal" data-target="#noteModal"><i class="fa fa-plus"></i></a></td>
                                    <td>

                                    <a href="{{ route('caller.lead.status',['id' => $lead->id, 'status' => 1]) }}"><i class="fa fa-check" aria-hidden="true"></i></a>
                                    <a href="{{ route('caller.lead.status',['id' => $lead->id, 'status' => 2]) }}"><i class="fa fa-times-circle" aria-hidden="true"></i></a>
                                    <a href="{{ route('caller.lead.status',['id' => $lead->id, 'status' => 3]) }}"><i class="fa fa-pause" aria-hidden="true"></i></a>

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
            <div class="modal fade" id="noteModal" tabindex="-1" role="dialog" aria-labelledby="noteModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form action="{{route('caller.lead.note.edit')}}" method="POST">
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

        </main>
    </div>
</div>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
<script>
    $(document).ready(function() {

        $('#example').DataTable({
            "ordering": false,
            "info": false,
            "lengthChange": false
        });


        $(".note-modal").click(function () {
            $("#modal-note-id").val(this.dataset.src)
            $("#modal-note").text(this.dataset.content)
        })
    })
</script>

<!-- Icons -->

</body>
</html>
