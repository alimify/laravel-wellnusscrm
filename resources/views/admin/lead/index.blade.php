@extends('layouts.admin.app')

@section('title','Leads')

@push('css')
    <link rel="stylesheet" href="{{asset('css/admin/jquery.ui.css')}}">
<style>
    input[readonly="readonly"] {
        border:0px;
    }
</style>
@endpush

@php
    $fromDate = \Carbon\Carbon::now()->startOfMonth()->format('d-m-Y');
    $toDate = \Carbon\Carbon::now()->format('d-m-Y');
@endphp

@section('content')

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
            </select>
        </div>

        <div class="table-responsive">
            <table id="example" class="table table-striped table-bordered" style="width:100%">
            </table>
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
                            <label for="status">Status</label>
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
                        product_id: item.product_id+(!item.update_admin && !item.update_caller ? `<span class="ml-3 badge badge-danger">new</span>`:''),
                        supplier_id: item.supplier_id,
                        supplier_name: item.supplier.name,
                        name: `${item.name ? item.name : ''}`,
                        phone: `${item.phone ? item.phone : ''}`,
                        email: `${item.email ? item.email : ''}`,
                        address: `${item.address ? item.address : ''}`,
                        note: `${item.note}<br/><a href="javascript:void(0)" class="note-modal" data-id="${item.id}" data-content="${item.note}"><i class="fa fa-plus"></i></a>`,
                        order_id: item.order_id,
                        publisher_id: item.publisher_id,
                        status_admin: `<span class="${item.admin_status.class}">${item.admin_status.title}</span>`,
                        status_caller: `<span class="${item.caller_status.class}">${item.caller_status.title}</span>`,
                        created_at: item.created_at,
                        action: !gType ? `<a title="Confirm" href="javascript:void(0)" class="ml-1 status-item" data-id="${item.id}" data-status="1"><i class="fa fa-check" aria-hidden="true"></i></a><a title="Cancel" href="javascript:void(0)" class="ml-1 status-item" data-id="${item.id}" data-status="2"><i class="fa fa-times-circle" aria-hidden="true"></i></a><a title="Hold" href="javascript:void(0)" class="ml-1 status-item" data-id="${item.id}" data-status="3"><i class="fa fa-pause" aria-hidden="true"></i></a><a title="Edit" href="javascript:void(0)" class="ml-1 edit-item" data-id="${item.id}" data-name="${item.name? item.name:''}" data-phone="${item.phone ? item.phone:'' }" data-email="${item.email?item.email:''}" data-address="${item.address?item.address:''}" data-status="${item.status_caller?item.status_caller:''}"><i class="fa fa-edit" aria-hidden="true"></i></a><a title="Trash" href="javascript:void(0)" class="delete-item ml-1" data-id="${item.id}"><i class="fa fa-trash" aria-hidden="true"></i></a>`:
                              `<a title="Restore" href="javascript:void(0)" class="restore-item ml-1" data-id="${item.id}"><i class="fa fa-undo" aria-hidden="true"></i></a> <a title="Delete Permanently" href="javascript:void(0)" class="delete-item ml-1" data-id="${item.id}"><i class="fa fa-trash" aria-hidden="true"></i></a>`
                    });
                })

                $('#example').DataTable({
                    data: data,
                    columns:[
                        {title:'',data:'checkbox'},
                        {title:'Product ID',data:'product_id'},
                        {title:'Order ID',data:'order_id'},
                        {title:'DateTime',data:'created_at'},
                        {title:'Supplier',data:'supplier_name'},
                        {title:'Customer',data:'name'},
                        {title:'Phone',data:'phone'},
                        {title:'Email',data:'email'},
                        {title:'Address',data:'address'},
                        {title:'Status Admin',data:'status_admin'},
                        {title:'Status Caller',data:'status_caller'},
                        {title:'Note',data:'note'},
                        {title:'Action',data:'action'},
                    ],
                    //ordering: false,
                    info:     false,
                    lengthChange: false,
                    order: [[ 3, "desc" ]],
                    columnDefs: [
                        { targets: 12, orderable: false, searchable: false },
                        { targets: 0, orderable: false, searchable: false, }
                    ]
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
                    $("#example").DataTable().destroy();
                    getURL(finalLeadURL(gStatus,gType),printLeadTable,errorMsg,errorMsg)
                }else{
                    errorMsg()
                }
            },
            deleteLead = function (a) {
                console.log(a.target.responseText)
                const data = JSON.parse(a.target.responseText);
              if(data.status){
                  $("#example").DataTable().destroy()
                  getURL(finalLeadURL(gStatus,gType),printLeadTable,errorMsg,errorMsg)
              }
            },
            restoreLead = function (a) {
                console.log(a.target.responseText)
                    const data = JSON.parse(a.target.responseText);
                    if(data.status){
                        $("#example").DataTable().destroy()
                        getURL(finalLeadURL(gStatus,gType),printLeadTable,errorMsg,errorMsg)
                    }
                },
            leadStatus = function (a) {
                console.log(a.target.responseText)
                const data = JSON.parse(a.target.responseText);
                if(data.status){
                    $("#example").DataTable().destroy()
                    getURL(finalLeadURL(gStatus,gType),printLeadTable,errorMsg,errorMsg)
                }
            },
            leadEdit = function (a) {
                console.log(a.target.responseText)
                const data = JSON.parse(a.target.responseText);
                if(data.status){
                    $("#example").DataTable().destroy()
                    getURL(finalLeadURL(gStatus,gType),printLeadTable,errorMsg,errorMsg)
                }
            },
            sendLead = function (a) {
                console.log(a.target.responseText)
                const data = JSON.parse(a.target.responseText);
                if(data.status){
                    $("#example").DataTable().destroy()
                    getURL(finalLeadURL(gStatus,gType),printLeadTable,errorMsg,errorMsg)
                }
            };




            var selected_lead = [];




        var    appURL = '{{route('index')}}',
               leadURI = '{{route('admin.lead.ajax')}}',
               leadURL = leadURI.replace(appURL,''),
               gType = false,
               gStatus = false,
               finalLeadURL = function(status = false,type = false){
                       gStatus = status
                       status = status ? `&status=${status}` : false
                       gType = type
                       type = type ? `&type=${type}` : false;

            return `${leadURL}?fromDate=${$("#fromDate").val()}&toDate=${$("#toDate").val()}${status ? status : ''}${type ? type : ''}`
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
            })



            $("body").on('click','.note-modal',function () {
                $("#modal-note-id").val(this.dataset.id)
                $("#modal-note").text(this.dataset.content)
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

            $("body").on('click','.delete-item',function () {
                const URL = `{{route('admin.lead.destroy','deleteit')}}`.replace('deleteit',this.dataset.id),
                       finalURL = URL.replace(appURL,''),
                       data  = {
                          _method:'DELETE',
                       }

                       getURL(finalURL,deleteLead,errorMsg,errorMsg,data)
            })


            $("body").on('click','.restore-item',function () {
                const URL = `{{route('admin.lead.restore.single','deleteit')}}`.replace('deleteit',this.dataset.id),
                    finalURL = URL.replace(appURL,''),
                    data  = {
                        _method:'POST',
                    }

                getURL(finalURL,restoreLead,errorMsg,errorMsg,data)
            })


            $("body").on('click','.status-item',function () {
                const URL = `{{route('admin.lead.status',['id' => 'leadid','status' => 'statusid'])}}`.replace('leadid',this.dataset.id).replace('statusid',this.dataset.status),
                    finalURL = URL.replace(appURL,''),
                    data  = {
                        _method:'POST',
                    }

                getURL(finalURL,leadStatus,errorMsg,errorMsg,data)
            })


            $("body").on('click','.edit-item',function () {
                $("#editModal #id").val(this.dataset.id)
                $("#editModal #name").val(this.dataset.name)
                $("#editModal #phone").val(this.dataset.phone)
                $("#editModal #email").val(this.dataset.email)
                $("#editModal #address").text(this.dataset.address)
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
                    console.log(data)

                getURL(finalURL,leadEdit,errorMsg,errorMsg,data)
                $("#editModal").modal('hide')
            })


            $("#fromDate").datepicker({ dateFormat: 'dd-mm-yy' })
            $("#toDate").datepicker({ dateFormat: 'dd-mm-yy' })

            $("#view-leads").click(function () {
                $("#example").DataTable().destroy();
                getURL(finalLeadURL(),printLeadTable,errorMsg,errorMsg)
            })

            $("#view-hold").click(function () {
                $("#example").DataTable().destroy();
                getURL(finalLeadURL(3),printLeadTable,errorMsg,errorMsg)
            })


            $("#view-cancelled").click(function () {
                $("#example").DataTable().destroy();
                getURL(finalLeadURL(2),printLeadTable,errorMsg,errorMsg)
            })


            $("#view-confirm").click(function () {
                $("#example").DataTable().destroy();
                getURL(finalLeadURL(1),printLeadTable,errorMsg,errorMsg)
            })

            $("#view-trash").click(function () {
                $("#example").DataTable().destroy();
                getURL(finalLeadURL(false,'trash'),printLeadTable,errorMsg,errorMsg)
            })

            $("#fromDate").change(function () {
                $("#example").DataTable().destroy();
                getURL(finalLeadURL(gStatus,gType),printLeadTable,errorMsg,errorMsg)
            })

            $("#toDate").change(function () {
                $("#example").DataTable().destroy();
                getURL(finalLeadURL(gStatus,gType),printLeadTable,errorMsg,errorMsg)
            })

        } );
    </script>
@endpush
