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
                        </table>
                    </div>

            @else
                <div class="text-warning text-center">No data available.</div>
        @endif

        <!-- Modal -->
            <div class="modal fade" id="noteModal" tabindex="-1" role="dialog" aria-labelledby="noteModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="noteModalLabel">Note</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input id="id" type="hidden" name="id">
                                <textarea class="form-control" id="note" name="note"></textarea>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" id="submit" class="btn btn-primary">Save changes</button>
                            </div>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="addressModal" tabindex="-1" role="dialog" aria-labelledby="addressModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addressModalLabel">Address</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input id="id" type="hidden" name="id">
                            <textarea class="form-control" id="note" name="note"></textarea>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" id="submit" class="btn btn-primary">Save changes</button>
                        </div>
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
        const  listen = (a, b, c , d = true) => {
                b.length && d ? b.forEach(e => { e.addEventListener(a, c) }) : b.addEventListener(a, c)
                return
            },

            getURL = function(url,load,error,abort,data = {}){
                let ajax,
                    formdata = new FormData();
                formdata.append('_token','{{csrf_token()}}')

                Object.keys(data).forEach(function (item) {
                    formdata.append(item,data[item])
                })

                try {
                    ajax = new XMLHttpRequest();
                } catch (t) {
                    try {
                        ajax = new ActiveXObject("Msxml2.XMLHTTP");
                    } catch (t) {
                        try {
                            ajax = new ActiveXObject("Microsoft.XMLHTTP");
                        } catch (t) {
                            console.log("Something error....");
                        }
                    }
                }

                listen("load", ajax, load);
                listen("error", ajax, error);
                listen("abort", ajax, abort);
                ajax.open("POST", url);
                ajax.send(formdata);
            },
        printTaskTable = function (a) {
            console.log(a.target.responseText)
            const  json = JSON.parse(a.target.responseText),
                    data = [];

            json.data.forEach(function (item) {
               data.push({
                   id:item.id,
                   product_id: item.product_id+(!item.update_caller ? `<span class="ml-3 badge badge-danger">new</span>`:''),
                   created_at: item.created_at,
                   name: item.name,
                   phone: item.phone,
                   email: item.email,
                   address: `${item.address ? item.address+'<br/>' : ''}<a href="javascript:void(0)" class="address-modal text-center" data-id="${item.id}" data-content="${item.address?item.address:''}"><i class="fa fa-plus"></i></a>`,
                   order_id: item.order_id,
                   note: `${item.note ? item.note+'<br/>' : ''}<a href="javascript:void(0)" class="note-modal text-center" data-id="${item.id}" data-content="${item.note}"><i class="fa fa-plus"></i></a>`,
                   status:`<span class="${item.caller_status.class}">${item.caller_status.title}</span>`,
                   action:`<a title="Confirm" href="javascript:void(0)" class="ml-1 status-item" data-id="${item.id}" data-status="1"><i class="fa fa-check" aria-hidden="true"></i></a><a title="Cancel" href="javascript:void(0)" class="ml-1 status-item" data-id="${item.id}" data-status="2"><i class="fa fa-times-circle" aria-hidden="true"></i></a><a title="Hold" href="javascript:void(0)" class="ml-1 status-item" data-id="${item.id}" data-status="3"><i class="fa fa-pause" aria-hidden="true"></i></a>`
               })
            })


            $('#example').DataTable({
                data: data,
                columns:[
                    {title:'Product ID',data:'product_id'},
                    {title:'Order ID',data:'order_id'},
                    {title:'DateTime',data:'created_at'},
                    {title:'Customer',data:'name'},
                    {title:'Phone',data:'phone'},
                    {title:'Email',data:'email'},
                    {title:'Address',data:'address'},
                    {title:'Status',data:'status'},
                    {title:'Note',data:'note'},
                    {title:'Action',data:'action'},
                ],
                //ordering: false,
                info:     false,
                lengthChange: false,
                order: [[ 2, "desc" ]],
                columnDefs: [
                    { targets: 9, orderable: false, searchable: false },
                    { targets: 0, orderable: false, searchable: false, }
                ]
            });

        },
        errorMSG = function (a) {

        },
        statusEdit = function (a) {
            console.log(a.target.responseText)
            const json = JSON.parse(a.target.responseText);

            if(json.status){
                $("#example").DataTable().destroy()
                getURL(taskURL,printTaskTable,errorMSG,errorMSG)
            }
        },
        noteEdit = function (a) {
            console.log(a.target.responseText)
            const json = JSON.parse(a.target.responseText);

            if(json.status){
                $("#example").DataTable().destroy()
                getURL(taskURL,printTaskTable,errorMSG,errorMSG)
            }
        },addressEdit = function (a) {
                console.log(a.target.responseText)
                const json = JSON.parse(a.target.responseText);

                if(json.status){
                    $("#example").DataTable().destroy()
                    getURL(taskURL,printTaskTable,errorMSG,errorMSG)
                }
            };


        var    appURL = '{{route('index')}}',
                taskURL = `{{route('caller.lead.ajax.data')}}`.replace(appURL,'');

                getURL(taskURL,printTaskTable,errorMSG,errorMSG)


        $("body").on('click','.status-item',function () {
            const URL = `{{route('caller.lead.status',['id' => 'leadid','status' => 'statusid'])}}`.replace('leadid',this.dataset.id).replace('statusid',this.dataset.status),
                   finalURL = URL.replace(appURL,'');
            getURL(finalURL,statusEdit,errorMSG,errorMSG)
        })


        $("body").on('click','.note-modal',function () {
            $("#noteModal #id").val(this.dataset.id)
            $("#noteModal #note").text(this.dataset.content)
            $("#noteModal").modal('show')
        })


        $("#noteModal #submit").click(function () {
            const URL = `{{route('caller.lead.note.edit')}}`,
                finalURL = URL.replace(appURL,''),
                data = {
                    id: $("#noteModal #id").val(),
                    note: $("#noteModal #note").val()
                };
            getURL(finalURL,noteEdit,errorMSG,errorMSG,data)
            $("#noteModal").modal('hide')
        })

        $("body").on('click','.address-modal',function () {
            $("#addressModal #id").val(this.dataset.id)
            $("#addressModal #note").text(this.dataset.content)
            $("#addressModal").modal('show')
        })


        $("#addressModal #submit").click(function () {
            const URL = `{{route('caller.lead.address.edit')}}`,
                   finalURL = URL.replace(appURL,''),
                  data = {
                    id: $("#addressModal #id").val(),
                    address: $("#addressModal #note").val()
                };
            getURL(finalURL,addressEdit,errorMSG,errorMSG,data)
            $("#addressModal").modal('hide')
        })

    })
</script>

<!-- Icons -->

</body>
</html>
