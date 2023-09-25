@extends('layouts.main')
@section('title', 'Add Recipe')
@section('content')
@push('head')
    <link rel="stylesheet" href="{{ asset('plugins/select2/dist/css/select2.min.css') }}">
@endpush

    <div class="container-fluid">
    	<div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="fas fa-receipt bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Add Recipe')}}</h5>

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
                        <h3>{{ __('Add Recipe')}}</h3>
                    </div>
                    <div class="card-body">
                        <form enctype="multipart/form-data" action="{{ route('recipes.store') }}" method="POST">
                            @csrf
                            <div class="row">

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="date">Date<span class="text-red">*</span></label>

                                        <input type="date" name="date" id="date" value="{{ old('date') }}" class="form-control @error('date') is-invalid @enderror" placeholder="Enter date" required>

                                        @error('date')
                                        <span class="text-danger" role="alert">
                                            <p>{{ $message }}</p>
                                        </span>
                                        @enderror

                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="product_id">Product<span class="text-red">*</span></label>

                                        <select name="product_id" id="product_id" class="form-control select2 @error('product_id') is-invalid @enderror" required>
                                            <option value="">Select Product</option>
                                            @foreach ($products as $key => $product)
                                                <option value="{{ $product->id }}" > {{ $product->name }} </option>
                                            @endforeach
                                        </select>

                                        @error('product_id')
                                        <span class="text-danger" role="alert">
                                            <p>{{ $message }}</p>
                                        </span>
                                        @enderror

                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="ingredient_id">Ingredient<span class="text-red">*</span></label>

                                        <select id="ingredient_id" class="form-control select2 @error('ingredient_id') is-invalid @enderror" >
                                            <option value="">Select Ingredient</option>
                                            @foreach ($ingredients as $key => $ingredient)
                                                <option value="{{ $ingredient->id }}" > {{ $ingredient->name }} </option>
                                            @endforeach
                                        </select>

                                        @error('ingredient_id')
                                        <span class="text-danger" role="alert">
                                            <p>{{ $message }}</p>
                                        </span>
                                        @enderror

                                    </div>
                                </div>

                            </div>

                            <div  id="AddField">

                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea name="description" id="description" class="form-control" cols="2" rows="2" placeholder="Write description">{!! old('description') !!}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-success mr-2">Create</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('script')
    <script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}"></script>
    <script>
    var i = 0 ;
    var removedItems = [];
    $('#ingredient_id').on('change', function(e){
            e.preventDefault();
            var ingredient_id = $("#ingredient_id").val();

            var selectedOption = $(this).find(":selected");
            selectedOption.remove();
            removedItems.push(selectedOption);

            var url = "{{ route('get-ingredient') }}";
            $.ajax({
                type: "get",
                url: url,
                data: {
                    ingredient_id: ingredient_id,
                },

                success: function(data) {
                i++;
                $("#AddField").append(`<div class="row" id="removed">
                <div class="col-sm-6">
                        <div class="form-group">
                            <label for="ingredient_id">Ingredient Name<span class="text-red">*</span></label>
                            <input type="text" id="ingredient${i}" value="${data.name}" class="form-control" readonly>
                            <input type="hidden" id="ingredient${i}" name="ingredient_id[]" value="${data.id}" class="form-control">
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="quantity">Quantity(${data.units.unit_name})<span class="text-red">*</span></label>

                            <div class="d-flex">
                                <input type="text" onkeyup="Quantity(${i})" name="quantity[]" id="quantity${i}" value="{{ old('quantity') }}" class="form-control quantity @error('quantity') is-invalid @enderror" placeholder="Enter quantity" required>

                                <button title="Remove Button" style="margin-left: 5px; height:35px; font-size:14px; text-align:center" type="button" name="del" id="del" class="btn btn-danger btn_remove">-</button>

                            @error('amount')
                            <span class="text-danger" role="alert">
                                <p>{{ $message }}</p>
                            </span>
                            @enderror
                        </div>

                        </div>
                    </div>

                </div>`);
                }
            });
        })

        $(document).on('click', '.btn_remove', function() {
            $(this).parents('#removed').remove();
            var lastRemovedOption = removedItems.pop();
            $("#ingredient_id").append(lastRemovedOption);
        });

</script>
@endpush
@endsection
