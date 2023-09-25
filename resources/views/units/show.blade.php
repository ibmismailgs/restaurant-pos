@extends('layouts.main')
@section('title', 'Units Details')
@section('content')

    <div class="container-fluid">
    	<div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="fa fa-balance-scale bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Units Details')}}</h5>

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
                        <h3 class="d-block w-100">{{ __('Units')}}

                            <small class="float-right">
                                <a title="Go Back" href="{{ URL::previous() }}" class="badge badge-secondary">
                                    <i class="ik ik-arrow-left"></i>
                                    Back
                                </a>

                                @can('manage_user')
                                    <a title="Create" href="{{ route('units.create') }}" class="badge badge-success">
                                        <i class="ik ik-plus"></i>
                                        Create
                                    </a>
                                @endcan
                            </small>
                        </h3>
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered table-striped table-hover col-md-12  text-center">
                            <thead>
                                <tr class="btn-primary text-center">
                                    <td colspan="2">Units Details</td>
                                </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td width="50%">Unit Name</td>
                                    <td width="50%" style="word-break: break-word;">{{ $data->unit_name ?? '--' }}</td>
                                </tr>

                                <tr>
                                    <td width="50%">Unit Value</td>
                                    <td width="50%" style="word-break: break-word;">{{ $data->unit_value ?? '--' }}</td>
                                </tr>

                                <tr>
                                    <td width="50%">Description</td>
                                    <td width="50%" style="word-break: break-word;">{!! $data->description ?? '--' !!}</td>
                                </tr>

                            </tbody>
                          </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
