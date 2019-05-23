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
    <title>My Leads Management</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{asset('css/admin/dashboard.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/admin/jquery.ui.css')}}">

</head>
@php
    $fromDate = \Carbon\Carbon::now()->startOfMonth()->format('d-m-Y');
    $toDate = \Carbon\Carbon::now()->format('d-m-Y');
@endphp
<body>
@include('layouts.admin.partials.topbar')

<div class="container-fluid justify-content-center mt-3">
    <div class="row">

        <main role="main" class="col-12 px-4">
            @include('layouts.admin.partials.notice')
            <div class="load-bar" id="load-bar" style="display: none;margin-top: -8px;">
                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>
            </div>


            <div class="text-center">
                My Leads | <a href="{{route('caller.all')}}"> All Leads</a>
            </div>

            <div class="text-center">
                <span class="btn btn-status-cancel"></span> Cancel
                <span class="btn btn-status-hold"></span> Hold
                <span class="btn btn-status-trash"></span> Trash
            </div>
            <h2>My Leads</h2>
            <a href="javascript:void(0)" id="view-leads">Leads </a>
            - <a href="javascript:void(0)" id="view-hold">Hold </a>
            <!-- <a href="javascript:void(0)" id="view-confirm">Confirm </a>-->
            - <a href="javascript:void(0)" id="view-cancelled">Cancelled </a>
            - <a href="javascript:void(0)" id="view-trash">Trash </a>
        <!--- <a href="{{route('admin.lead.restore.all')}}">Restore All</a>-->

            <div class="filtering form-inline justify-content-center">
                <input type="text" class="form-control col-xs-10 col-sm-2 m-1" id="fromDate" value="{{$fromDate}}">
                <input type="text" class="form-control col-xs-10 col-sm-2 m-1" id="toDate" value="{{$toDate}}">
                </select>
            </div>

            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                </table>

                <div class="form-inline">
                    <input type="number" id="page-form-number" class="form-control col-sm-3 col-md-1" min="1" value="1">
                    <input type="button" id="page-form-button" class="btn btn-action btn-success" value="JUMP">
                </div>
            </div>


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
<script src="{{asset('js/admin/jquery.ui.min.js')}}"></script>
<script>
    $.fn.DataTable.ext.pager.numbers_length = 20;
</script>
<script>
    $(document).ready(function() {
        const  listen = (a, b, c , d = true) => {
                b.length && d ? b.forEach(e => { e.addEventListener(a, c) }) : b.addEventListener(a, c)
                return
            },

            getURL = function(url,load,error,abort,data = {}){
                $("#load-bar").css('display','block')

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


                listen("load", ajax, function () {
                    $("#load-bar").css('display','none')
                });
                listen("error", ajax, function () {
                    $("#load-bar").css('display','none')
                });
                listen("abort", ajax, function () {
                    $("#load-bar").css('display','none')
                });


                ajax.open("POST", url);
                ajax.send(formdata);
            },
            printTaskTable = function (a) {

                const  json = JSON.parse(a.target.responseText),
                    data = [];

                json.data.forEach(function (item) {
                    data.push({
                        id:item.id,
                        product: (item.product_id ? item.product.name : '')+(!item.update_caller ? `<span class="badge badge-pill badge-danger">new</span>`:'')+(item.doublel ? `<span class="badge badge-pill badge-success">double</span>`:''),
                        created_at: item.created_at,
                        name: item.name,
                        phone: item.phone,
                        //email: item.email,
                        address: `${item.address ? item.address+'<br/>' : ''}<a href="javascript:void(0)" class="address-modal text-center" data-id="${item.id}" data-content="${item.address?item.address:''}"><i class="fa fa-plus"></i></a>`,
                        order_id: item.order_id,
                        note: `${item.note ? item.note+'<br/>' : ''}<a href="javascript:void(0)" class="note-modal text-center" data-id="${item.id}" data-content="${item.note}"><i class="fa fa-plus"></i></a>`,
                        status:`<span class="${item.caller_status.class}"></span>`,
                        action:`<a title="Confirm" href="javascript:void(0)" class="ml-1 status-item" data-id="${item.id}" data-status="1"><i class="fa fa-check" aria-hidden="true"></i></a><a title="Cancel" href="javascript:void(0)" class="ml-1 status-item" data-id="${item.id}" data-status="2"><i class="fa fa-times-circle" aria-hidden="true"></i></a><a title="Hold" href="javascript:void(0)" class="ml-1 status-item" data-id="${item.id}" data-status="3"><i class="fa fa-pause" aria-hidden="true"></i></a><a title="Trash" href="javascript:void(0)" class="status-item ml-1" data-id="${item.id}" data-status="4"><i class="fa fa-trash" aria-hidden="true"></i></a>`
                    })
                })


                $('#example').DataTable({
                    data: data,
                    destroy: true,
                    bDestroy: true,
                    columns:[
                        {title:'Product',data:'product'},
                        {title:'Order ID',data:'order_id'},
                        {title:'DateTime',data:'created_at'},
                        {title:'Customer',data:'name'},
                        {title:'Phone',data:'phone'},
                        //{title:'Email',data:'email'},
                        {title:'Address',data:'address'},
                        {title:'Status',data:'status'},
                        {title:'Note',data:'note'},
                        {title:'Action',data:'action'},
                    ],
                    //ordering: false,
                    info:     true,
                    lengthChange: false,
                    order: [[ 2, "desc" ]],
                    columnDefs: [
                        { targets: 8, orderable: false, searchable: false },
                        { targets: 0, orderable: false, searchable: false, }
                    ],
                    fnDrawCallback: function(pinfo){
                        const pageinfo = this.api().page.info()
                        $("#page-form-number").val(pageinfo.page+1)
                        currentPageNumber = pageinfo.page
                    }
                });

            },
            errorMSG = function (a) {

            },
            statusEdit = function (a) {

                const json = JSON.parse(a.target.responseText);

                if(json.status){
                    rowUpdate(json.lead)
                    // getURL(finalTaskURL(gStatus),printTaskTable,errorMSG,errorMSG)
                }
            },
            noteEdit = function (a) {

                const json = JSON.parse(a.target.responseText);

                if(json.status){
                    rowUpdate(json.lead)
                    //getURL(finalTaskURL(gStatus),printTaskTable,errorMSG,errorMSG)
                }
            },addressEdit = function (a) {

                const json = JSON.parse(a.target.responseText);

                if(json.status){
                    rowUpdate(json.lead)
                    //getURL(finalTaskURL(gStatus),printTaskTable,errorMSG,errorMSG)
                }
            },
            rowUpdate = function (a) {
                const data = $("#example").DataTable().row(row_effected).data();
                data.product = (a.product_id ? a.product.name : '')+(!a.update_caller ? `<span class="ml-3 badge badge-danger">new</span>`:'')
                data.note = `${a.note ? a.note+'<br/>' : ''}<a href="javascript:void(0)" class="note-modal text-center" data-id="${a.id}" data-content="${a.note}"><i class="fa fa-plus"></i></a>`
                data.address = `${a.address ? a.address+'<br/>' : ''}<a href="javascript:void(0)" class="address-modal text-center" data-id="${a.id}" data-content="${a.address?a.address:''}"><i class="fa fa-plus"></i></a>`
                data.status = `<span class="${a.caller_status.class}"></span>`

                //data.action  = `<span class="btn-action d-block"><a title="Confirm" href="javascript:void(0)" class="status-item" data-id="${a.id}" data-status="1"><i class="fa fa-check" aria-hidden="true"></i></a></span><span class="btn-action d-block"><a title="Cancel" href="javascript:void(0)" class="status-item" data-id="${a.id}" data-status="2"><i class="fa fa-times-circle" aria-hidden="true"></i></a></span><span class="btn-action d-block"><a title="Hold" href="javascript:void(0)" class="status-item" data-id="${a.id}" data-status="3"><i class="fa fa-pause" aria-hidden="true"></i></a></span><span class="btn-action d-block"><a title="Edit" href="javascript:void(0)" class="edit-item" data-id="${a.id}" data-name="${a.name? a.name:''}" data-phone="${a.phone ? a.phone:'' }" data-email="${a.email?a.email:''}" data-address="${a.address?a.address:''}" data-status="${a.status_caller?a.status_caller:''}"><i class="fa fa-edit" aria-hidden="true"></i></a></span><span class="btn-action d-block"><a title="Trash" href="javascript:void(0)" class="status-item" data-id="${a.id}" data-status="4"><i class="fa fa-trash" aria-hidden="true"></i></a></span>`

                $("#example").DataTable().row(row_effected).data(data).invalidate()
            },
            printAPage = function (a) {
                var table = $('#example').DataTable();
                table.page( parseInt(a) ).draw( 'page' );
            };


        var  row_effected,
            currentPageNumber;

        var    appURL = '{{route('index')}}',
            taskURL = `{{route('caller.lead.index.ajax.data')}}`.replace(appURL,''),
            gStatus = false,
            finalTaskURL = function (status = false) {
                gStatus = status
                status = status ? '&status='+status : false
                return `${taskURL}?fromDate=${$("#fromDate").val()}&toDate=${$("#toDate").val()}${status ? status : ''}`
            };

        getURL(finalTaskURL(),printTaskTable,errorMSG,errorMSG)


        $("body").on('click','.status-item',function () {
            const URL = `{{route('caller.lead.status',['id' => 'leadid','status' => 'statusid'])}}`.replace('leadid',this.dataset.id).replace('statusid',this.dataset.status),
                finalURL = URL.replace(appURL,'');
            row_effected = this.parentElement.parentElement

            getURL(finalURL,statusEdit,errorMSG,errorMSG)
        })


        $("body").on('click','.note-modal',function () {
            row_effected = this.parentElement.parentElement

            $("#noteModal #id").val(this.dataset.id)
            $("#noteModal #note").val(this.dataset.content)

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
            row_effected = this.parentElement.parentElement
            $("#addressModal #id").val(this.dataset.id)
            $("#addressModal #note").val(this.dataset.content)
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

        $("#view-leads").click(function () {
            getURL(finalTaskURL(false),printTaskTable,errorMSG,errorMSG)
        })

        $("#view-hold").click(function () {
            getURL(finalTaskURL(3),printTaskTable,errorMSG,errorMSG)
        })

        $("#view-confirm").click(function () {
            getURL(finalTaskURL(1),printTaskTable,errorMSG,errorMSG)
        })

        $("#view-cancelled").click(function () {
            getURL(finalTaskURL(2),printTaskTable,errorMSG,errorMSG)

        })

        $("#view-trash").click(function () {
            getURL(finalTaskURL(4),printTaskTable,errorMSG,errorMSG)
        })


        $("#fromDate").datepicker({ dateFormat: 'dd-mm-yy' })
        $("#toDate").datepicker({ dateFormat: 'dd-mm-yy' })


        $("#fromDate").change(function () {
            getURL(finalTaskURL(gStatus),printTaskTable,errorMSG,errorMSG)
        })

        $("#toDate").change(function () {
            getURL(finalTaskURL(gStatus),printTaskTable,errorMSG,errorMSG)
        })


        $("#page-form-button").click(function () {
            printAPage($("#page-form-number").val()-1)
        })

    })
</script>

<!-- Icons -->

</body>
</html>
