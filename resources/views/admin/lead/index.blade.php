@extends('layouts.admin.app')

@section('title','Leads')

@push('css')
    <link rel="stylesheet" href="{{asset('css/admin/jquery.ui.css')}}">
    <style>
        table.dataTable td{
            padding: 2px!important;
        }
        table.dataTable th:nth-child(11),table.dataTable th:nth-child(12){
            padding-left: 2px!important;
            padding-right: 2px!important;
        }
        .btn-action {
            font-size: 16px;
        }
    </style>
@endpush

@php
    $fromDate = \Carbon\Carbon::now()->startOfMonth()->format('d-m-Y');
    $toDate = \Carbon\Carbon::now()->format('d-m-Y');
@endphp

@section('content')
    <div class="load-bar" id="load-bar" style="display: none;margin-top:-8px;">
        <div class="bar"></div>
        <div class="bar"></div>
        <div class="bar"></div>
    </div>
    <div class="text-center">
        <span class="btn btn-status-confirm"></span> Confirm
        <span class="btn btn-status-cancel"></span> Cancel
        <span class="btn btn-status-hold"></span> Hold
        <span class="btn btn-status-trash"></span> Trash
    </div>
        <h2>Leads</h2>
        <a href="javascript:void(0)" id="view-leads">Leads </a>
        - <a href="javascript:void(0)" id="view-hold">Hold </a>
        - <a href="javascript:void(0)" id="view-confirm">Confirm </a>
        - <a href="javascript:void(0)" id="view-cancelled">Cancelled </a>
        - <a href="javascript:void(0)" id="view-trash">Trash </a>
        <!--- <a href="{{route('admin.lead.restore.all')}}">Restore All</a>-->

        <div class="filtering form-inline justify-content-center">
            <input type="text" class="form-control col-xs-10 col-sm-2 m-1" id="fromDate" value="{{$fromDate}}">
            <input type="text" class="form-control col-xs-10 col-sm-2 m-1" id="toDate" value="{{$toDate}}">
        </div>


        <div class="table-responsive display no-wrap">
            <table id="example" class="table table-striped table-bordered">
            </table>
        <div class="form-inline">
            <input type="number" id="page-form-number" class="form-control col-sm-3 col-md-1" min="1" value="1">
            <input type="button" id="page-form-button" class="btn btn-action btn-success" value="JUMP">
        </div>
        </div>
    <div class="justify-content-center form-inline mb-5 mt-3">
    <select id="callerId" class="form-control col-md-3 col-sm-8 form-inline">
        @foreach($callers as $caller)
            <option value="{{$caller->id}}">{{$caller->name}}</option>
            @endforeach
    </select>
        <button type="button" id="send-to-caller" class="btn btn-primary btn-success form-inline">Submit</button>
    </div>


    <!-- Add/Edit Note Modal -->
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
                        <input id="modal-note-id" type="hidden" name="id">
                         <textarea class="form-control" id="modal-note" name="note"></textarea>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="submit-note-modal">Save changes</button>
                </div>
            </div>
        </div>
    </div>


        <!-- Edit Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="noteModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="noteModalLabel">Edit</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" class="form-control" id="id">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" class="form-control" id="name" placeholder="Name">
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" name="phone" class="form-control" id="phone" placeholder="Phone">
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" name="email" class="form-control" id="email" placeholder="Email">
                        </div>

                        <div class="form-group">
                            <label for="name">Address</label>
                            <textarea name="address" class="form-control" rows="1" id="address"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="status">Caller Status</label>
                            <select name="status" id="status" class="form-control">
                                @foreach($statuses as $status)
                                    <option value="{{$status->id}}">{{$status->title}}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="submit-modal">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

@endsection


@push('script')
<script src="{{asset('js/admin/jquery.ui.min.js')}}"></script>
    <script>
        $(document).ready(function() {


            const  listen = (a, b, c , d = true) => {
                    b.length && d ? b.forEach(e => { e.addEventListener(a, c) }) : b.addEventListener(a, c)
                    return
                },

                getURL = function(url,load,error,abort,data = {}){

                /*Before Sending Ajax*/
                 $("#load-bar").css('display','')
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


                printLeadTable =   function (a){
                console.log(a.target.responseText)
                const json = JSON.parse(a.target.responseText),
                    data = [];
                json.data.forEach(function (item) {
                    data.push({
                        checkbox: `<input type="checkbox" value="${item.id}" class="select_item">`,
                        id: item.id,
                        product_serial: item.proudct_serial,
                        supplier_serial: item.supplier_serial,
                        product: (item.product_id ? item.product.name : '')+(!item.update_admin && !item.update_caller ? `<span class="badge badge-pill badge-danger">new</span>`:'')+(item.doublel ? `<span class="badge badge-pill badge-success">double</span>`:''),
                        supplier_id: item.supplier_id,
                        supplier_name: item.supplier != null ? item.supplier.name : '',
                        name: `${item.name ? item.name : ''}`,
                        phone: `${item.phone ? item.phone : ''}`,
                        //email: `${item.email ? item.email : ''}`,
                        address: `${item.address ? item.address : ''}`,
                        note: `${item.note}<br/><a href="javascript:void(0)" class="note-modal" data-id="${item.id}" data-content="${item.note}"><i class="fa fa-plus"></i></a>`,
                        order_id: item.order_id,
                        publisher_id: item.publisher_id,
                        status_admin: `<span class="${item.admin_status.class}" title="${item.admin_status.title}"></span>`,
                        status_caller: `<span class="${item.caller_status.class}" title="${item.caller_status.title}"></span>`,
                        created_at: item.created_at,
                        action: `<span class="btn-action d-block"><a title="Confirm" href="javascript:void(0)" class="status-item" data-id="${item.id}" data-status="1"><i class="fa fa-check" aria-hidden="true"></i></a></span><span class="btn-action d-block"><a title="Cancel" href="javascript:void(0)" class="status-item" data-id="${item.id}" data-status="2"><i class="fa fa-times-circle" aria-hidden="true"></i></a></span><span class="btn-action d-block"><a title="Hold" href="javascript:void(0)" class="status-item" data-id="${item.id}" data-status="3"><i class="fa fa-pause" aria-hidden="true"></i></a></span><span class="btn-action d-block"><a title="Edit" href="javascript:void(0)" class="edit-item" data-id="${item.id}" data-name="${item.name? item.name:''}" data-phone="${item.phone ? item.phone:'' }" data-email="${item.email?item.email:''}" data-address="${item.address?item.address:''}" data-status="${item.status_caller?item.status_caller:''}"><i class="fa fa-edit" aria-hidden="true"></i></a></span><span class="btn-action d-block"><a title="Trash" href="javascript:void(0)" class="status-item" data-id="${item.id}" data-status="4"><i class="fa fa-trash" aria-hidden="true"></i></a></span>`
                    });
                })

                $('#example').DataTable({
                    destroy: true,
                    bDestroy: true,
                    autoWidth: false,
                    data: data,
                    columns:[
                        {title:'',data:'checkbox',width:'20px',class:'text-center'},
                        {title:'Product',data:'product',width:'50px'},
                        {title:'OrderID',data:'order_id',width:'50px'},
                        {title:'DateTime',data:'created_at',width:'50px'},
                        {title:'Supplier',data:'supplier_name',width:'50px'},
                        {title:'Customer',data:'name',width:'50px'},
                        {title:'Phone',data:'phone',width:'50px'},
                        //{title:'Email',data:'email'},
                        {title:'Address',data:'address',width:'80px'},
                        {title:'Note',data:'note',width:'150px'},
                        {title:'Action',data:'action',width:'50px',class:'text-center'},
                        {title:'Admin Status',data:'status_admin',width:'30px',class:'text-center'},
                        {title:'Caller Status',data:'status_caller',width:'30px',class:'text-center'},
                    ],
                    //ordering: false,
                    info:     true,
                    lengthChange: false,
                    order: [[ 3, "desc" ]],
                    columnDefs: [
                        { targets: [0,9,10,11], orderable: false, searchable: false},
                        {targets:[2,6,7,8],orderable:false}
                    ],
                    fnDrawCallback: function(pinfo){
                        const pageinfo = this.api().page.info()
                        $("#page-form-number").val(pageinfo.page+1)
                        currentPageNumber = pageinfo.page
                    }
                });
            },

            errorMsg =    function (){
                console.log('an error')
            },

            noteEdit =  function(a){
                console.log(a.target.responseText)
                const data = JSON.parse(a.target.responseText);
                $("#noteModal").modal('hide')
                if(data.status){
                    rowUpdate(data.lead)
                   // getURL(finalLeadURL(gStatus),printLeadTable,errorMsg,errorMsg)
                }else{
                    errorMsg()
                }
            },
            leadStatus = function (a) {
                console.log(a.target.responseText)
                const data = JSON.parse(a.target.responseText);
                if(data.status){
                    rowUpdate(data.lead)
                    ///$("#example").DataTable().destroy()
                    //getURL(finalLeadURL(gStatus),printLeadTable,errorMsg,errorMsg)
                }
            },
            leadEdit = function (a) {
                console.log(a.target.responseText)
                const data = JSON.parse(a.target.responseText);
                if(data.status){
                    rowUpdate(data.lead)
                    //$("#example").DataTable().destroy()
                    //getURL(finalLeadURL(gStatus),printLeadTable,errorMsg,errorMsg)
                }
            },
            sendLead = function (a) {
                console.log(a.target.responseText)
                const data = JSON.parse(a.target.responseText);
                if(data.status){
                    $("#example").DataTable().destroy()
                    getURL(finalLeadURL(gStatus),printLeadTable,errorMsg,errorMsg)
                }
            },
            rowUpdate = function (a) {
                console.log(a)
                const data = $("#example").DataTable().row(row_effected).data();
                data.name = a.name
                data.note = `${a.note}<br/><a href="javascript:void(0)" class="note-modal" data-id="${a.id}" data-content="${a.note}"><i class="fa fa-plus"></i></a>`
                data.phone = a.phone
                data.address = a.address
                data.status_admin = `<span class="${a.admin_status.class}" title="${a.admin_status.title}"></span>`
                data.status_caller   = `<span class="${a.caller_status.class}" title="${a.caller_status.title}"></span>`
                data.action  = `<span class="btn-action d-block"><a title="Confirm" href="javascript:void(0)" class="status-item" data-id="${a.id}" data-status="1"><i class="fa fa-check" aria-hidden="true"></i></a></span><span class="btn-action d-block"><a title="Cancel" href="javascript:void(0)" class="status-item" data-id="${a.id}" data-status="2"><i class="fa fa-times-circle" aria-hidden="true"></i></a></span><span class="btn-action d-block"><a title="Hold" href="javascript:void(0)" class="status-item" data-id="${a.id}" data-status="3"><i class="fa fa-pause" aria-hidden="true"></i></a></span><span class="btn-action d-block"><a title="Edit" href="javascript:void(0)" class="edit-item" data-id="${a.id}" data-name="${a.name? a.name:''}" data-phone="${a.phone ? a.phone:'' }" data-email="${a.email?a.email:''}" data-address="${a.address?a.address:''}" data-status="${a.status_caller?a.status_caller:''}"><i class="fa fa-edit" aria-hidden="true"></i></a></span><span class="btn-action d-block"><a title="Trash" href="javascript:void(0)" class="status-item" data-id="${a.id}" data-status="4"><i class="fa fa-trash" aria-hidden="true"></i></a></span>`
                $("#example").DataTable().row(row_effected).data(data).invalidate()
                console.log(data)
            },
            printAPage = function (a) {
                var table = $('#example').DataTable();
                table.page(parseInt(a)).draw('page');
            };




            var selected_lead = [],
                 row_effected,
                 currentPageNumber;




        var    appURL = '{{route('index')}}',
               leadURI = '{{route('admin.lead.ajax')}}',
               leadURL = leadURI.replace(appURL,''),
               gStatus = false,
               finalLeadURL = function(status = false){
                       gStatus = status
                       status = status ? `&status=${status}` : false

            return `${leadURL}?fromDate=${$("#fromDate").val()}&toDate=${$("#toDate").val()}${status ? status : ''}`
               };
        getURL(finalLeadURL(),printLeadTable,errorMsg,errorMsg)




            $("body").on('click','.select_item',function () {
                if(this.checked){
                    selected_lead.push(this.value)
                }else{
                  selected_lead.pop(this.value)
                }
                console.log(selected_lead)
            })



            $("#send-to-caller").click(function () {
                const URL = `{{route('admin.lead.sendTask')}}`,
                    finalURL = URL.replace(appURL,''),
                    data  = {
                        _method:'POST',
                        callerId:$("#callerId").val(),
                        task:selected_lead
                    }

                getURL(finalURL,sendLead,errorMsg,errorMsg,data)
                selected_lead = []
            })



            $("body").on('click','.note-modal',function () {
                row_effected = this.parentElement.parentElement
                $("#modal-note-id").val(this.dataset.id)
                $("#modal-note").val(this.dataset.content)
                $("#noteModal").modal('show')
            })


            $("#submit-note-modal").click(function () {
                const URL = `{{route('admin.lead.note.edit')}}`,
                       finalURL = URL.replace(appURL,''),
                       data = {
                           id: $("#modal-note-id").val(),
                           note: $("#modal-note").val()
                       };
                getURL(finalURL,noteEdit,errorMsg,errorMsg,data)
            })



            $("body").on('click','.status-item',function () {
                const URL = `{{route('admin.lead.status',['id' => 'leadid','status' => 'statusid'])}}`.replace('leadid',this.dataset.id).replace('statusid',this.dataset.status),
                    finalURL = URL.replace(appURL,''),
                    data  = {
                        _method:'POST',
                    }
                row_effected = this.parentElement.parentElement.parentElement
                getURL(finalURL,leadStatus,errorMsg,errorMsg,data)
            })


            $("body").on('click','.edit-item',function () {
                $("#editModal #id").val(this.dataset.id)
                $("#editModal #name").val(this.dataset.name)
                $("#editModal #phone").val(this.dataset.phone)
                $("#editModal #email").val(this.dataset.email)
                $("#editModal #address").val(this.dataset.address)
                row_effected = this.parentElement.parentElement.parentElement
                const status = this.dataset.status
                $("#editModal #status option").each(function () {

                    if($(this).val() == status){
                        $(this).prop('selected', true);
                    }
                })
               $("#editModal").modal('show')
            })

            $("#editModal #submit-modal").click(function () {
                const URL = `{{route('admin.lead.update','leadid')}}`.replace('leadid',$("#editModal #id").val()),
                    finalURL = URL.replace(appURL,''),
                    data  = {
                        _method:'PUT',
                        name:$("#editModal #name").val(),
                        phone:$("#editModal #phone").val(),
                        email:$("#editModal #email").val(),
                        address:$("#editModal #address").val(),
                        caller_status:$("#editModal #status").val(),
                    }
                getURL(finalURL,leadEdit,errorMsg,errorMsg,data)
                $("#editModal").modal('hide')
            })


            $("#fromDate").datepicker({ dateFormat: 'dd-mm-yy' })
            $("#toDate").datepicker({ dateFormat: 'dd-mm-yy' })

            $("#view-leads").click(function () {
                getURL(finalLeadURL(),printLeadTable,errorMsg,errorMsg)
            })

            $("#view-hold").click(function () {
                getURL(finalLeadURL(3),printLeadTable,errorMsg,errorMsg)
            })


            $("#view-cancelled").click(function () {
                getURL(finalLeadURL(2),printLeadTable,errorMsg,errorMsg)
            })


            $("#view-confirm").click(function () {
                getURL(finalLeadURL(1),printLeadTable,errorMsg,errorMsg)
            })

            $("#view-trash").click(function () {
                getURL(finalLeadURL(4),printLeadTable,errorMsg,errorMsg)
            })

            $("#fromDate").change(function () {
                getURL(finalLeadURL(gStatus),printLeadTable,errorMsg,errorMsg)
            })

            $("#toDate").change(function () {
                getURL(finalLeadURL(gStatus),printLeadTable,errorMsg,errorMsg)
            })

            $("#page-form-button").click(function () {
                printAPage($("#page-form-number").val()-1)
            })

        } );
    </script>
@endpush
