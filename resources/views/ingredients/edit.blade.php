@extends('layouts.main')
@section('title', 'Edit Ingredients')
@section('content')

    <div class="container-fluid">
    	<div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="fa fa-gift bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Edit Ingredients')}}</h5>

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
                        <h3>{{ __('Edit Ingredients')}}</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{route('ingredients.update', $data->id)}}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="parts_code"> {{ __('Ingredients Name') }}
                                        <span class="text-red">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter ingredients name" value="{{ $data->name }}" required>
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="unit">{{ __("Unit") }}<span class="text-red">*</span>
                                    </label>
                                    <select id="unit_id" name="unit_id" class="form-control select2 @error('unit_id') is-invalid @enderror" required>
                                        <option value="">Select Unit</option>

                                        @foreach ($units as $key => $unit)
                                            <option value="{{ $unit->id }}" @if($data->unit_id == $unit->id) selected @endif> {{ $unit->unit_name }}</option>
                                        @endforeach

                                    </select>
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
