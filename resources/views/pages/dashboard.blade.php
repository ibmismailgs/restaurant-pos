@extends('layouts.main')
@section('title', 'Dashboard')
@section('content')
    <!-- push external head elements to head -->
    @push('head')
        <link rel="stylesheet" href="{{ asset('plugins/DataTables/datatables.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/weather-icons/css/weather-icons.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/owl.carousel/dist/assets/owl.carousel.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/owl.carousel/dist/assets/owl.theme.default.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/chartist/dist/chartist.min.css') }}">
    @endpush

    <div class="container-fluid">
    	<div class="row">
    		<!-- page statustic chart start -->
            <div class="col-xl-3 col-md-6">
                <div class="card card-red text-white">
                    <div class="card-block">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h4 class="mb-0">{{ $products }}</h4>
                                <p class="mb-0">{{ __('Products')}}</p>
                            </div>
                            <div class="col-4 text-right">
                                <i class="fas fa-cube f-30"></i>
                            </div>
                        </div>
                        <div id="Widget-line-chart1" class="chart-line chart-shadow"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-blue text-white">
                    <div class="card-block">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h4 class="mb-0">{{ $orders }}</h4>
                                <p class="mb-0">{{ __('Orders')}}</p>
                            </div>
                            <div class="col-4 text-right">
                                <i class="ik ik-shopping-cart f-30"></i>
                            </div>
                        </div>
                        <div id="Widget-line-chart2" class="chart-line chart-shadow" ></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-green text-white">
                    <div class="card-block">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h4 class="mb-0">{{ $purchaseAmount }}</h4>
                                <p class="mb-0">{{ __('Purchase Amount')}}</p>
                            </div>
                            <div class="col-4 text-right">
                                <i class="ik f-30">৳</i>
                            </div>
                        </div>
                        <div id="Widget-line-chart3" class="chart-line chart-shadow"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-yellow text-white">
                    <div class="card-block">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h4 class="mb-0">{{ $saleAmount }}</h4>
                                <p class="mb-0">{{ __('Sales Amount')}}</p>
                            </div>
                            <div class="col-4 text-right">
                                <i class="ik f-30">৳</i>
                            </div>
                        </div>
                        <div id="Widget-line-chart4" class="chart-line chart-shadow" ></div>
                    </div>
                </div>
            </div>

    	</div>

        <div class="row">

            <div class="col-md-12">
                <div class="card p-3">
                    <div class="card-header">
                        <h3 class="d-block w-100">{{ __('Product')}}
                        </h3>
                    </div>

                    <div class="card-body">
                        <table id="data_table" class="table table-bordered table-striped data-table table-hover">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Name</th>
                                    <th>Unit</th>
                                    <th>Price</th>
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
	<!-- push external js -->
    @push('script')
        <script src="{{ asset('plugins/DataTables/datatables.min.js') }}"></script>
        <script src="{{ asset('plugins/owl.carousel/dist/owl.carousel.min.js') }}"></script>
        <script src="{{ asset('plugins/chartist/dist/chartist.min.js') }}"></script>
        <script src="{{ asset('plugins/flot-charts/jquery.flot.js') }}"></script>
        <!-- <script src="{{ asset('plugins/flot-charts/jquery.flot.categories.js') }}"></script> -->
        <script src="{{ asset('plugins/flot-charts/curvedLines.js') }}"></script>
        <script src="{{ asset('plugins/flot-charts/jquery.flot.tooltip.min.js') }}"></script>

        <script src="{{ asset('plugins/amcharts/amcharts.js') }}"></script>
        <script src="{{ asset('plugins/amcharts/serial.js') }}"></script>
        <script src="{{ asset('plugins/amcharts/themes/light.js') }}"></script>


        <script src="{{ asset('js/widget-statistic.js') }}"></script>
        <script src="{{ asset('js/widget-data.js') }}"></script>
        <script src="{{ asset('js/dashboard-charts.js') }}"></script>
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
                    url: "{{route('product.index')}}",
                    type: "get"
                },

                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: true},
                    {data: 'name', name: 'name' , searchable: true},
                    {data: 'unit_name', name: 'unit_name', searchable: false},
                    {data: 'price', name: 'price', searchable: false},
                ],
                dom: "<'row'<'col-sm-2'l><'col-sm-7 text-center'B><'col-sm-3'f>>tipr",
                        buttons: [
                                {
                                    extend: 'copy',
                                    className: 'btn-sm btn-info',
                                    title: 'Product List',
                                    header: true,
                                    footer: true,
                                    exportOptions: {
                                        columns: ['0,1,2,3'],
                                    }
                                },
                                {
                                    extend: 'csv',
                                    className: 'btn-sm btn-success',
                                    title: 'Product List',
                                    header: true,
                                    footer: true,
                                    exportOptions: {
                                        columns: ['0,1,2,3'],
                                    }
                                },
                                {
                                    extend: 'excel',
                                    className: 'btn-sm btn-dark',
                                    title: 'Product List',
                                    header: true,
                                    footer: true,
                                    exportOptions: {
                                        columns: ['0,1,2,3'],
                                    }
                                },
                                {
                                    extend: 'pdf',
                                    className: 'btn-sm btn-primary',
                                    title: 'Product List',
                                    pageSize: 'A2',
                                    header: true,
                                    footer: true,
                                    exportOptions: {
                                        columns: ['0,1,2,3'],
                                    }
                                },
                                {
                                    extend: 'print',
                                    className: 'btn-sm btn-danger',
                                    title: 'Product List',
                                    pageSize: 'A2',
                                    header: true,
                                    footer: true,
                                    orientation: 'landscape',
                                    exportOptions: {
                                        columns: ['0,1,2,3'],
                                        stripHtml: false
                                    }
                                }
                            ],
                });
            });


        </script>
    @endpush
@endsection
