@extends('layouts.main')
@section('title', 'Sale Reports')
@section('content')
@push('head')
<link rel="stylesheet" href="{{ asset('plugins/DataTables/datatables.min.css') }}">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
        <style>
            .ui-datepicker-calendar {
            display: none;
        }
        </style>
@endpush
    <div class="container-fluid">
    	<div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-file bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Sale Reports')}}</h5>

                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{route('dashboard')}}" class="btn btn-outline-success" title="Home"><i class="ik ik-home"></i></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ url()->previous() }}" class="btn btn-outline-danger" title="Go Back"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
                            </li>
                        </ol>
                    </nav>
                </div>

            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="start_date"><b>Start Date</b>  <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}"
                                class="form-control @error('start_date') is-invalid @enderror"
                                placeholder="Enter start date" required>

                            @error('start_date')
                                <span class="text-danger" role="alert">
                                    <p>{{ $message }}</p>
                                </span>
                            @enderror

                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="end_date"><b>End Date</b>  <span class="text-danger">*</span></label>
                            <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}"
                                class="form-control @error('end_date') is-invalid @enderror"
                                placeholder="Enter end date" required>

                            @error('end_date')
                                <span class="text-danger" role="alert">
                                    <p>{{ $message }}</p>
                                </span>
                            @enderror

                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="month"> Select Month <span class="text-red">*</span></label>
                                <input type="text" name="month" id="month" value="{{ old('month') }}" class="form-control datepicker" placeholder="choose month">
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="today">Today's Report<span class="text-red">*</span></label>
                                <input title="Button" type="button" name="today" id="today" value="{{ date('Y-m-d') }}" class="btn btn-sm btn-success" placeholder="Click Here">
                        </div>
                    </div>

                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-primary btn-sm" type="submit" id="search" name="search"><i class="fa fa-search"></i> Search</button>
                        </div>
                    </div>
            </div>
        </div>
        <div class="row">

            <div class="col-md-12">
                <div class="card p-3">
                    <div class="card-header">
                        <h3 class="d-block w-100">{{ __('Sale Reports')}}

                            <small class="float-right">
                                <a title="Go Back" href="{{ URL::previous() }}" class="badge badge-secondary">
                                    <i class="ik ik-arrow-left"></i>
                                    Back
                                </a>
                            </small>
                        </h3>
                    </div>

                    <div class="card-body">
                        <table id="data_table" class="table table-bordered table-striped data-table table-hover">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Product Name</th>
                                    <th>Product Unit</th>
                                    <th>Total Quantity</th>
                                    <th>Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tfoot>
                                    <tr id="total_tr">
                                        <td class="tg-0lax" colspan="3"></td>
                                        <td class="tg-0lax" style="background: #f2f2f2">Total Amount</td>
                                        <td class="tg-0lax" style="background: #f2f2f2"><b id="totalAmount"> </b></td>

                                        </tr>
                                </tfoot>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@push('script')
<script src="{{ asset('plugins/DataTables/datatables.min.js') }}"></script>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
<script src="{{ asset('js/alerts.js')}}"></script>
<script src="{{ asset('plugins/sweetalert/dist/sweetalert.min.js') }}"></script>

<script>
     $('#start_date, #end_date').on('change',function(event){
        event.preventDefault();
        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();

        var currentDate = new Date().toISOString().slice(0, 10);

        if(start_date > currentDate){
            swal({
                title: 'Error!!!',
                text: "Start date must be less than current date",
                dangerMode: true,
            });
            $('#start_date').val('null');
        }

        if(end_date > currentDate) {
            swal({
                title: 'Error!!!',
                text: "End date must be less than or equal current date",
                dangerMode: true,
            });
            $('#end_date').val('null');
        }
    });

// date picker
    $(function() {
        $('.datepicker').datepicker( {
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        yearRange:"-30:+100",
        dateFormat: 'MM-yy',
        onClose: function(dateText, inst) {
            $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
        }
        });
    });

    $(document).ready( function () {
    var dTable = $('#data_table').DataTable({
        order: [],
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        processing: true,
        responsive: false,
        serverSide: true,
        scroller: {
            loadingIndicator: false
        },
        language: {
              processing: '<i class="ace-icon fa fa-spinner fa-spin orange bigger-500" style="font-size:60px;margin-top:50px;"></i>'
            },
        pagingType: "full_numbers",
        ajax: {
            url: "{{route('sale-report')}}",
            type: "get"
        },

        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: true},
            {data: 'productName', name: 'productName', searchable: false},
            {data: 'unit_name', name: 'unit_name', searchable: false},
            {data: 'totalQuantity', name: 'totalQuantity', searchable: false},
            {data: 'totalAmount', name: 'totalAmount', searchable: false},
        ],
        dom: "<'row'<'col-sm-2'l><'col-sm-7 text-center'B><'col-sm-3'f>>tipr",
                buttons: [
                        {
                            extend: 'copy',
                            className: 'btn-sm btn-info',
                            title: 'Sale Report',
                            header: true,
                            footer: true,
                            exportOptions: {
                                columns: ['0,1,2,3,4'],
                            }
                        },
                        {
                            extend: 'csv',
                            className: 'btn-sm btn-success',
                            title: 'Sale Report',
                            header: true,
                            footer: true,
                            exportOptions: {
                                columns: ['0,1,2,3,4'],
                            }
                        },
                        {
                            extend: 'excel',
                            className: 'btn-sm btn-dark',
                            title: 'Sale Report',
                            header: true,
                            footer: true,
                            exportOptions: {
                                columns: ['0,1,2,3,4'],
                            }
                        },
                        {
                            extend: 'pdf',
                            className: 'btn-sm btn-primary',
                            title: 'Sale Report',
                            pageSize: 'A2',
                            header: true,
                            footer: true,
                            exportOptions: {
                                columns: ['0,1,2,3,4'],
                            }
                        },
                        {
                            extend: 'print',
                            className: 'btn-sm btn-danger',
                            title: 'Sale Report',
                            pageSize: 'A2',
                            header: true,
                            footer: true,
                            orientation: 'landscape',
                            exportOptions: {
                                columns: ['0,1,2,3,4'],
                                stripHtml: false
                            }
                        }
                    ],
                    initComplete: function(data) {
                    var totalAmount = data.json.totalAmount;
                    $('#total_tr').show()
                    document.getElementById('totalAmount').innerHTML = totalAmount;
                },
        });
    });

    $('#search').on('click',function(event){
			event.preventDefault();
			var end_date = $("#end_date").val();
			var start_date = $("#start_date").val();
			var month = $("#month").val();

            var dTable = $('.table').DataTable({
                order: [],
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                processing: true,
                responsive: false,
                serverSide: true,
                "bDestroy": true,
                language: {
                processing: '<i class="ace-icon fa fa-spinner fa-spin orange bigger-500" style="font-size:60px;margin-top:50px;"></i>'
                },
                scroller: {
                    loadingIndicator: false
                },
                pagingType: "full_numbers",
                ajax: {
                    url: "{{ route('monthly-sale-report') }}",
                    type: "GET",
                    data:{
                        'start_date':start_date,
                        'end_date':end_date,
                        'month':month,
                    },
                },
                columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: true},
                {data: 'productName', name: 'productName', searchable: false},
                {data: 'unit_name', name: 'unit_name', searchable: false},
                {data: 'totalQuantity', name: 'totalQuantity', searchable: false},
                {data: 'totalAmount', name: 'totalAmount', searchable: false},
                ],
                dom: "<'row'<'col-sm-2'l><'col-sm-7 text-center'B><'col-sm-3'f>>tipr",
                buttons: [
                        {
                            extend: 'copy',
                            className: 'btn-sm btn-info',
                            title: 'Sale Report',
                            header: true,
                            footer: true,
                            exportOptions: {
                                columns: ['0,1,2,3,4'],
                            }
                        },
                        {
                            extend: 'csv',
                            className: 'btn-sm btn-success',
                            title: 'Sale Report',
                            header: true,
                            footer: true,
                            exportOptions: {
                                columns: ['0,1,2,3,4'],
                            }
                        },
                        {
                            extend: 'excel',
                            className: 'btn-sm btn-dark',
                            title: 'Sale Report',
                            header: true,
                            footer: true,
                            exportOptions: {
                                columns: ['0,1,2,3,4'],
                            }
                        },
                        {
                            extend: 'pdf',
                            className: 'btn-sm btn-primary',
                            title: 'Sale Report',
                            pageSize: 'A2',
                            header: true,
                            footer: true,
                            exportOptions: {
                                columns: ['0,1,2,3,4'],
                            }
                        },
                        {
                            extend: 'print',
                            className: 'btn-sm btn-danger',
                            title: 'Sale Report',
                            pageSize: 'A2',
                            header: true,
                            footer: true,
                            orientation: 'landscape',
                            exportOptions: {
                                columns: ['0,1,2,3,4'],
                                stripHtml: false
                            }
                        }
                    ],
                initComplete: function(data) {
                    var totalAmount = data.json.totalAmount;
                    $('#total_tr').show()
                    document.getElementById('totalAmount').innerHTML = totalAmount;
                },

        });
		});

    $('#today').on('click',function(event){
			event.preventDefault();
			$("#start_date").val(' ');
			$("#end_date").val(' ');
			$("#month").val(' ');
			var today = $("#today").val();

            var dTable = $('.table').DataTable({
                order: [],
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                processing: true,
                responsive: false,
                serverSide: true,
                "bDestroy": true,
                language: {
                processing: '<i class="ace-icon fa fa-spinner fa-spin orange bigger-500" style="font-size:60px;margin-top:50px;"></i>'
                },
                scroller: {
                    loadingIndicator: false
                },
                pagingType: "full_numbers",
                ajax: {
                    url: "{{ route('monthly-sale-report') }}",
                    type: "GET",
                    data:{
                        'today':today,
                    },
                },
                columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: true},
                {data: 'productName', name: 'productName', searchable: false},
                {data: 'unit_name', name: 'unit_name', searchable: false},
                {data: 'totalQuantity', name: 'totalQuantity', searchable: false},
                {data: 'totalAmount', name: 'totalAmount', searchable: false},
                ],
                dom: "<'row'<'col-sm-2'l><'col-sm-7 text-center'B><'col-sm-3'f>>tipr",
                buttons: [
                        {
                            extend: 'copy',
                            className: 'btn-sm btn-info',
                            title: 'Sale Report',
                            header: true,
                            footer: true,
                            exportOptions: {
                                columns: ['0,1,2,3,4'],
                            }
                        },
                        {
                            extend: 'csv',
                            className: 'btn-sm btn-success',
                            title: 'Sale Report',
                            header: true,
                            footer: true,
                            exportOptions: {
                                columns: ['0,1,2,3,4'],
                            }
                        },
                        {
                            extend: 'excel',
                            className: 'btn-sm btn-dark',
                            title: 'Sale Report',
                            header: true,
                            footer: true,
                            exportOptions: {
                                columns: ['0,1,2,3,4'],
                            }
                        },
                        {
                            extend: 'pdf',
                            className: 'btn-sm btn-primary',
                            title: 'Sale Report',
                            pageSize: 'A2',
                            header: true,
                            footer: true,
                            exportOptions: {
                                columns: ['0,1,2,3,4'],
                            }
                        },
                        {
                            extend: 'print',
                            className: 'btn-sm btn-danger',
                            title: 'Sale Report',
                            pageSize: 'A2',
                            header: true,
                            footer: true,
                            orientation: 'landscape',
                            exportOptions: {
                                columns: ['0,1,2,3,4,5'],
                                stripHtml: false
                            }
                        }
                    ],
                initComplete: function(data) {
                    var totalAmount = data.json.totalAmount;
                    $('#total_tr').show()
                    document.getElementById('totalAmount').innerHTML = totalAmount;
                },
        });
		});
</script>
@endpush
@endsection
