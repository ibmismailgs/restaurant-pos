@extends('layouts.main')
@section('title', 'Purchase Details')
@section('content')

    <div class="container-fluid">
    	<div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="fa fa-shopping-cart bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Purchase Details')}}</h5>

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
                        <h3 class="d-block w-100">{{ __('Purchase')}}

                            <small class="float-right">
                                <a title="Go Back" href="{{ URL::previous() }}" class="badge badge-secondary">
                                    <i class="ik ik-arrow-left"></i>
                                    Back
                                </a>

                                @can('manage_user')
                                    <a title="Create" href="{{ route('purchase.create') }}" class="badge badge-success">
                                        <i class="ik ik-plus"></i>
                                        Create
                                    </a>
                                @endcan
                            </small>
                        </h3>
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered table-striped table-hover col-md-12 ">
                            <thead>
                                <tr class="btn-primary text-center">
                                    <td colspan="5">Purchase</td>
                                </tr>
                                <tr>
                                    <th>Date</th>
                                    <th>Reference No</th>
                                    <th>Total Quantity</th>
                                    <th>Total Amount</th>
                                    <th>Description</th>
                              </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>{{ Carbon\Carbon::parse($purchase->date)->format('d F, Y') ?? '--' }}</th>
                                    <th>{{ $purchase->ref_no ?? '--' }}</th>
                                    <th>{{ $purchase->total_quantity ?? '--' }}</th>
                                    <th>{{ $purchase->total_amount }}</th>
                                    <th>{{ $purchase->description ?? '--' }}</th>
                                </tr>

                            </tbody>
                          </table>
                        <table class="table table-bordered table-striped table-hover col-md-12">
                            <thead>
                                <tr class="btn-primary text-center">
                                    <td colspan="5">Purchase Details</td>
                                </tr>

                                <tr>
                                    <th>#</th>
                                    <th>Ingredient</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Amount</th>
                              </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchaseDetails as $key => $purchaseDetail)
                                    <tr>
                                        <th>{{ $key + 1 }}</th>
                                        <th>{{ $purchaseDetail->ingredients->name}}</th>
                                        <th>{{ $purchaseDetail->quantity }}</th>
                                        <th>{{ $purchaseDetail->unit_price }}</th>
                                        <th>{{ $purchaseDetail->amount }}</th>
                                    </tr>
                                @endforeach
                            </tbody>
                          </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
