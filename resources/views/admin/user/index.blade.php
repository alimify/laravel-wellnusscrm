@extends('layouts.admin.app')

@section('title','User')

@push('css')


@endpush


@section('content')
    <a href="{{route('admin.user.create')}}" class="btn btn-success mt-4 mb-4">Add</a>
    @if($users->count() || $trashes->count())
    <h2>All Users</a></h2>
    <a href="{{route('admin.user.index')}}">Active Users ({{$users->count()}})</a>
    - <a href="{{route('admin.user.index',['type' => 'trash'])}}">Trash ({{$trashes->count()}})</a>
    - <a href="{{route('admin.user.restore.all')}}">Restore All</a>

    <div class="table-responsive">
        <table id="example" class="table table-striped table-bordered" style="width:100%">
            <thead>
            <tr>
                <th>#ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>

        @foreach((isset($_REQUEST['type']) && $_REQUEST['type'] == 'trash' ? $trashes : $users) as $user)
            <tr>
                <td>{{$user->id}}</td>
                <td>{{$user->name}}</td>
                <td>{{$user->Userextra->phone}}</td>
                <td>{{$user->email}}</td>
                <td>{{$user->Role->title}}</td>
                <td>
                    @if(isset($_REQUEST['type']) && $_REQUEST['type'] == 'trash')

                        <a href="{{route('admin.user.restore.single',$user->id)}}" class="restore-item" data-src="{{$user->id}}"><i class="fa fa-undo" aria-hidden="true"></i></a>

                    @else

                    <a href="{{route('admin.user.edit',$user->id)}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                    @endif

                    <a href="javascript:void(0)" class="delete-item" data-src="{{$user->id}}"><i class="fa fa-trash" aria-hidden="true"></i></a>
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
            "ordering": false,
            "info":     false,
            "lengthChange": false
        });


        $(".delete-item").click(function () {

            let deleteForm = document.createElement('form'),
                currentURL = `{{route('admin.user.destroy','deleteid')}}`,
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
