@extends('layouts.main')
@section('title', 'Edit Units')
@section('content')

    <div class="container-fluid">
    	<div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="fa fa-balance-scale bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Edit Units')}}</h5>

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
            <!-- start message area-->
            @include('include.message')
            <!-- end message area-->
            <div class="col-md-12">
                <div class="card ">
                    <div class="card-header">
                        <h3>{{ __('Edit Units')}}</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{route('units.update', $data->id)}}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="unit_name"> {{ __('Unit Name') }}
                                        <span class="text-red">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="unit_name" name="unit_name" placeholder="example: kg" value="{{ $data->unit_name }}" required>
                                    @error('unit_name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="unit_value">Unit Value<span class="text-red">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="unit_value" name="unit_value" placeholder="example: 1000" value="{{ $data->unit_value }}" required>
                                    @error('unit_value')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>

                            <div class="from-row">
                                <div class="form-group">
                                    <label for="description">{{ __('Description') }}</label>
                                    <textarea name="description"  class="form-control" id=" class="form-control"" cols="2" rows="2" placeholder="Write description">{{ $data->description }}</textarea>
                                </div>
                            </div>

                            <div class="row mt-10">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn form-bg-success mr-2">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
