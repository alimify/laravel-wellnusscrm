@extends('layouts.admin.app')

@section('title','Show')

@push('css')

<script src="{{asset('js/admin/piechart.js')}}"></script>
@endpush


@section('content')
    <div class="text-center">
        <span class="btn btn-status-confirm"></span> Confirm
        <span class="btn btn-status-cancel"></span> Cancel
        <span class="btn btn-status-hold"></span> Hold
        <span class="btn btn-status-trash"></span> Trash
    </div>
    <div id="piechart" style="height: 500px;"></div>


    <div class="table-responsive">
        <table id="example" class="table table-striped table-bordered" style="width:100%">
            <thead>
            <tr>
                <th>#ID</th>
                <th>Order ID</th>
                <th>DateTime</th>
                <th>Supplier</th>
                <th>Customer</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Address</th>
                <th>Status Admin</th>
                <th>Status Caller</th>
                <th>Note</th>
            </tr>
            </thead>
            <tbody>
            @foreach($leads as $lead)
                <tr>
                    <td>{{$lead->supplier_serial}}</td>
                    <td>{{$lead->order_id}}</td>
                    <td>{{$lead->created_at}}</td>
                    <td>{{$lead->Supplier->name??''}}</td>
                    <td>{{$lead->name}}</td>
                    <td>{{$lead->phone}}</td>
                    <td>{{$lead->email}}</td>
                    <td>{{$lead->address}}</td>
                    <td><span class="{{$lead->AdminStatus->class??''}}" title="{{$lead->AdminStatus->title??''}}"></span></td>
                    <td><span class="{{$lead->CallerStatus->class??''}}" title="{{$lead->CallerStatus->title??''}}"></span></td>
                    <td>{{$lead->note}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection


@push('script')
    <script type="text/javascript">
        $(document).ready(function() {

            google.charts.load("current", {packages: ["corechart"]});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var data = google.visualization.arrayToDataTable(<?php echo json_encode($chart); ?>);

                var options = {
                    title: `{{$product->name}} #{{$product->id}}`,
                    is3D: true,
                };

                var chart = new google.visualization.PieChart(document.getElementById('piechart'));
                chart.draw(data, options);
            }

            $('#example').DataTable({
                destroy: true,
                bDestroy: true,
                "ordering": false,
                "info": false,
                "lengthChange": false
            });
        })
    </script>
@endpush
