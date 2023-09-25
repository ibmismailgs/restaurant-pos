@extends('layouts.main')
@section('title', 'Sales List')
@section('content')
@push('head')
<link rel="stylesheet" href="{{ asset('plugins/DataTables/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/jquery-toast-plugin/dist/jquery.toast.min.css')}}">
@endpush
    <div class="container-fluid">
    	<div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-user-plus bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Sales List')}}</h5>

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
        <div class="row">

            <div class="col-md-12">
                <div class="card p-3">
                    <div class="card-header">
                        <h3 class="d-block w-100">{{ __('Sales')}}

                            <small class="float-right">
                                <a title="Go Back" href="{{ URL::previous() }}" class="badge badge-secondary">
                                    <i class="ik ik-arrow-left"></i>
                                    Back
                                </a>

                                @can('manage_user')
                                    <a title="Create" href="{{ route('sales.create') }}" class="badge badge-success">
                                        <i class="ik ik-plus"></i>
                                        Create
                                    </a>
                                @endcan
                            </small>
                        </h3>
                    </div>

                    <div class="card-body">
                        <table id="data_table" class="table table-bordered table-striped data-table table-hover">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Date</th>
                                    <th>Customer Name</th>
                                    <th>Customer Mobile</th>
                                    <th>Total Item</th>
                                    <th>Total Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@push('script')
<script src="{{ asset('plugins/DataTables/datatables.min.js') }}"></script>
<script src="{{ asset('plugins/jquery-toast-plugin/dist/jquery.toast.min.js')}}"></script>
<script src="{{ asset('js/alerts.js')}}"></script>
<script src="{{ asset('plugins/sweetalert/dist/sweetalert.min.js') }}"></script>
<script>
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
            url: "{{route('sales.index')}}",
            type: "get"
        },

        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: true},
            {data: 'date', name: 'date', searchable: false},
            {data: 'customer_name', name: 'customer_name', searchable: false},
            {data: 'customer_mobile', name: 'customer_mobile', searchable: false},
            {data: 'total_quantity', name: 'total_quantity', searchable: false},
            {data: 'grand_total', name: 'grand_total', searchable: false},
            {data: 'action', searchable: false, orderable: false}
        ],
        dom: "<'row'<'col-sm-2'l><'col-sm-7 text-center'B><'col-sm-3'f>>tipr",
                buttons: [
                        {
                            extend: 'copy',
                            className: 'btn-sm btn-info',
                            title: 'Sales List',
                            header: true,
                            footer: true,
                            exportOptions: {
                                columns: ['0,1,2,3,4,5'],
                            }
                        },
                        {
                            extend: 'csv',
                            className: 'btn-sm btn-success',
                            title: 'Sales List',
                            header: true,
                            footer: true,
                            exportOptions: {
                                columns: ['0,1,2,3,4,5'],
                            }
                        },
                        {
                            extend: 'excel',
                            className: 'btn-sm btn-dark',
                            title: 'Sales List',
                            header: true,
                            footer: true,
                            exportOptions: {
                                columns: ['0,1,2,3,4,5'],
                            }
                        },
                        {
                            extend: 'pdf',
                            className: 'btn-sm btn-primary',
                            title: 'Sales List',
                            pageSize: 'A2',
                            header: true,
                            footer: true,
                            exportOptions: {
                                columns: ['0,1,2,3,4,5'],
                            }
                        },
                        {
                            extend: 'print',
                            className: 'btn-sm btn-danger',
                            title: 'Sales List',
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
        });
    });

    $('#data_table').on('click', '#delete[href]', function (e) {
            e.preventDefault();
            var url = $(this).attr('href');
            swal({
                    title: `Are you sure ?`,
                    text: "Want to delete this record?",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'json',
                    data: {submit: true, _method: 'delete', _token: "{{ csrf_token() }}"}
                }).always(function (data) {
                    $('#data_table').DataTable().ajax.reload();
                    if (data.success === true) {
                        showSuccessToast(data.message);
                    }else{
                        showDangerToast(data.message);
                    }
                });
            }
            });
        });


    @if(Session::has('success'))
        showSuccessToast("{{ Session::get('success') }}");
    @elseif(Session::has('error'))
        showDangerToast("{{ Session::get('error') }}");
    @endif
</script>
@endpush
@endsection
