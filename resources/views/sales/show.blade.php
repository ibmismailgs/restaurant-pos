@extends('layouts.main')
@section('title', 'Sales Details')
@section('content')
@push('head')
<link rel="stylesheet" href="{{ asset('plugins/jquery-toast-plugin/dist/jquery.toast.min.css')}}">
@endpush

    <div class="container-fluid">
    	<div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-user-plus bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Sales Details')}}</h5>
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

                                <a title="Invoice" target="_blank" href="{{ route('sales-kitchen-order', $sale->id) }}" class="badge badge-primary">
                                    <i class="ik ik-file"></i>
                                    Kitchen
                                </a>

                                @can('manage_user')
                                    <a title="Invoice" target="_blank" href="{{ route('sales-invoice', $sale->id) }}" class="badge badge-success">
                                        <i class="ik ik-file"></i>
                                        Generate Invoice
                                    </a>
                                @endcan
                            </small>
                        </h3>
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered table-striped table-hover col-md-12 ">
                            <thead>
                                <tr class="btn-primary text-center">
                                    <td colspan="5">Sale</td>
                                </tr>
                                <tr>
                                    <th>Order No</th>
                                    <th>{{ $sale->order_no ?? '--' }}</th>
                                </tr>
                                <tr>
                                    <th>Date</th>
                                    <th>{{ Carbon\Carbon::parse($sale->date)->format('d F, Y') ?? '--' }}</th>
                                </tr>
                                <tr>
                                    <th>Customer Name</th>
                                    <th>{{ $sale->customer_name ?? '--' }}</th>
                                </tr>
                                <tr>
                                    <th>Customer Mobile</th>
                                    <th>{{ $sale->customer_mobile ?? '--' }}</th>
                                </tr>
                                <tr>
                                    <th>Total Item</th>
                                    <th>{{ $sale->total_quantity ?? '--' }}</th>
                                </tr>
                                <tr>
                                    <th>Discount Amount </th>
                                    <th>{{ $sale->discount }}</th>
                                </tr>
                                <tr>
                                    <th>Vat</th>
                                    <th>{{ $sale->tax_amount }}</th>
                                </tr>
                                <tr>
                                    <th>Total Amount</th>
                                    <th>{{ $sale->grand_total }}</th>
                                </tr>

                                <tr>
                                    <th>Payment Type</th>
                                    <th>
                                        @if ($sale->payment_type == 1)
                                            Cash
                                        @else
                                            Card
                                        @endif
                                    </th>
                                </tr>
                                @if ($sale->transaction != null)
                                <tr>
                                    <th>Transaction ID</th>
                                    <th> {{ $sale->transaction_id }}</th>
                                </tr>
                                @endif

                                </tr>
                                    <th>Note</th>
                                    <th>{{ $sale->note ?? '--' }}</th>
                                </tr>
                            </thead>

                          </table>
                        <table class="table table-bordered table-striped table-hover col-md-12">
                            <thead>
                                <tr class="btn-primary text-center">
                                    <td colspan="5">Sale Details</td>
                                </tr>
                                <tr>
                                    <th>#</th>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Amount</th>
                              </tr>
                            </thead>
                            <tbody>
                                @foreach ($saleDetails as $key => $saleDetail)
                                    <tr>
                                        <th>{{ $key + 1 }}</th>
                                        <th>{{ $saleDetail->products->name }}</th>
                                        <th>{{ $saleDetail->quantity }}</th>
                                        <th>{{ $saleDetail->amount }}</th>
                                    </tr>
                                @endforeach
                            </tbody>
                          </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('script')
    <script src="{{ asset('plugins/jquery-toast-plugin/dist/jquery.toast.min.js')}}"></script>
    <script src="{{ asset('js/alerts.js')}}"></script>
    <script>
        @if(Session::has('success'))
            showSuccessToast("{{ Session::get('success') }}");
        @elseif(Session::has('error'))
            showDangerToast("{{ Session::get('error') }}");
        @endif
    </script>
@endpush
@endsection
