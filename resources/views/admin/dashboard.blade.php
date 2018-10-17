@extends('layouts.admin.app')

@section('title','Dashboard')

@push('css')
    <link rel="stylesheet" href="{{asset('css/admin/jquery.ui.css')}}">
    <style>
        input[readonly="readonly"] {
            border:0px;
        }
    </style>
    <script src="{{asset('js/admin/piechart.js')}}"></script>
@endpush


@section('content')
    <div class="load-bar" id="load-bar" style="display: none;margin-top:-8px;">
        <div class="bar"></div>
        <div class="bar"></div>
        <div class="bar"></div>
    </div>
    <div class="filtering form-inline justify-content-center">
        <input type="text" class="form-control col-xs-10 col-sm-2 m-1" id="fromDate">
        <input type="text" class="form-control col-xs-10 col-sm-2 m-1" id="toDate">

        <select id="supplier" class="form-control col-xs-10 col-sm-2 m-1 form-inline">
            <option value="">Supplier</option>
            @foreach($suppliers as $supplier)
                <option value="{{$supplier->id}}">{{$supplier->name}}</option>
            @endforeach
        </select>
        <select id="product" class="form-control col-xs-10 col-sm-2 m-1 form-inline">
            <option value="">Product</option>
            @foreach($products as $product)
                <option value="{{$product->id}}">{{$product->name}}</option>
            @endforeach
        </select>
        <select id="status" class="form-control col-xs-10 col-sm-2 m-1 form-inline">
            <option value="">Status</option>
            @foreach($statuses as $status)
                <option value="{{$status->id}}">{{$status->title}}</option>
            @endforeach
        </select>
    </div>
    <div id="piechart" style="height: 500px;"></div>
@endsection


@push('script')
    <script src="{{asset('js/admin/jquery.ui.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function() {



            const  listen = (a, b, c , d = true) => {
                    b.length && d ? b.forEach(e => { e.addEventListener(a, c) }) : b.addEventListener(a, c)
                    return
                },

                getURL = function(url,load,error,abort,data = {}){
                    console.log(url)
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

            printChart = function (a) {
                console.log(a.target.responseText)
                const json = JSON.parse(a.target.responseText);

                google.charts.load("current", {packages:["corechart"]});
                google.charts.setOnLoadCallback(drawChart);
                function drawChart() {
                    var data = google.visualization.arrayToDataTable([
                        ['Task', 'Leads Chart'],
                        ['Confirmed',     json[1]],
                        ['Hold',      json[3]],
                        ['Cancelled',  json[2]],
                        ['Trash', json[4]]
                    ]);

                    var options = {
                        title: '',
                        is3D: true,
                        slices: {
                            0: {color: 'green'},
                            1: {color: 'orange'},
                            2: {color: 'red'},
                            3: {color: 'maroon'}
                        }
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('piechart'));
                    chart.draw(data, options);
                }
            },errorMSG = function (a) {

                };



            $("#fromDate").datepicker({ dateFormat: 'dd-mm-yy' })
            $("#toDate").datepicker({ dateFormat: 'dd-mm-yy' })

            var    appURL     = '{{route('index')}}',
                    leadURI   = '{{route('admin.dashboard.ajax')}}',
                    leadURL   = leadURI.replace(appURL,''),
                    finalURL  = function () {
                        return leadURL+`?fromDate=${$("#fromDate").val()}&toDate=${$("#toDate").val()}&supplier=${$("#supplier").val()}&product=${$("#product").val()}&status=${$("#status").val()}`;
                    }

                    getURL(finalURL(),printChart,errorMSG,errorMSG)

            $("#fromDate,#toDate,#supplier,#product,#status").change(function () {
                getURL(finalURL(),printChart,errorMSG,errorMSG)
            })

        })
    </script>
@endpush
