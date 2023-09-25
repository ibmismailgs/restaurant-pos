@extends('layouts.main')
@section('title', 'Edit Purchase')
@section('content')
@push('head')
    <link rel="stylesheet" href="{{ asset('plugins/select2/dist/css/select2.min.css') }}">
@endpush

    <div class="container-fluid">
    	<div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="fa fa-shopping-cart bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Edit Purchase')}}</h5>

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
                        <h3>{{ __('Edit Purchase')}}</h3>
                    </div>
                    <div class="card-body">
                        <form enctype="multipart/form-data" action="{{ route('purchase.update', $purchase->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="date">Date<span class="text-red">*</span></label>

                                        <input type="date" name="date" id="date" value="{{ $purchase->date }}" class="form-control @error('date') is-invalid @enderror" placeholder="Enter date" required>

                                        @error('date')
                                        <span class="text-danger" role="alert">
                                            <p>{{ $message }}</p>
                                        </span>
                                        @enderror

                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="ref_no">Reference No<span class="text-red">*</span></label>

                                        <input type="text" name="ref_no" id="ref_no" value="{{ $purchase->ref_no }}" class="form-control @error('ref_no') is-invalid @enderror" placeholder="Enter reference no" required>

                                        @error('ref_no')
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
                                                <option value="{{ $ingredient->id }}" @if(in_array($ingredient->id, $previousIngredients)) disabled @endif> {{ $ingredient->name }} </option>
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

                            @foreach ($purchaseDetails as $key => $purchaseDetail)

                            <div class="row row_del" >
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="ingredient_id">Ingredient<span class="text-red">*</span></label>
                                        <input type="text" value="{{ $purchaseDetail->ingredients->name }}" class="form-control" readonly>

                                        <input name="ingredient_id[]" type="hidden" value="{{ $purchaseDetail->ingredients->id }}" id="previous_ingredient" class="form-control previous_ingredient">
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="quantity">Quantity<span class="text-red">*</span></label>

                                        <input type="hidden" name="unit_value[]" value="{{ $purchaseDetail->ingredients->units->unit_value }}" id="unit_value">

                                        <input type="text" oninput="PreQuantity({{ $key }})" name="quantity[]" id="preQuantity{{ $key }}" value="{{ $purchaseDetail->quantity }}" class="form-control quantity @error('quantity') is-invalid @enderror" placeholder="Enter quantity" required>

                                        @error('quantity')
                                        <span class="text-danger" role="alert">
                                            <p>{{ $message }}</p>
                                        </span>
                                        @enderror

                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="unit_price">Unit Price<span class="text-red">*</span></label>

                                        <input type="text" oninput="PreUnitPrice({{ $key }})" name="unit_price[]" id="preUnitPrice{{ $key }}" value="{{ $purchaseDetail->unit_price }}" class="form-control unit_price @error('unit_price') is-invalid @enderror" placeholder="Enter unit price" required>

                                        @error('unit_price')
                                        <span class="text-danger" role="alert">
                                            <p>{{ $message }}</p>
                                        </span>
                                        @enderror

                                    </div>
                                </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                        <label for="amount">Amount<span class="text-red">*</span></label>
                                     <div class="d-flex">
                                            <input type="text" name="amount[]" id="preAmount{{ $key }}" value="{{ $purchaseDetail->amount}}" class="form-control amount @error('amount') is-invalid @enderror" placeholder="Enter unit price" readonly>

                                            {{-- <button style="margin-left: 5px; height:35px; font-size:14px; text-align:center" type="button" name="row_remove" id="row_remove" class="btn btn-danger row_remove">-</button> --}}

                                        @error('amount')
                                        <span class="text-danger" role="alert">
                                            <p>{{ $message }}</p>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        <div  id="AddField">

                        </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="total_quantity">Total Quantity<span class="text-red">*</span></label>

                                        <input type="text" name="total_quantity" id="total_quantity" value="{{ $purchase->total_quantity }}" class="form-control @error('total_quantity') is-invalid @enderror" placeholder="total quantity" readonly>

                                        @error('total_quantity')
                                        <span class="text-danger" role="alert">
                                            <p>{{ $message }}</p>
                                        </span>
                                        @enderror

                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="total_amount">Total Amount<span class="text-red">*</span></label>

                                        <input type="text" name="total_amount" id="total_amount" value="{{ $purchase->total_amount }}" class="form-control @error('total_amount') is-invalid @enderror" placeholder="total amount" readonly>

                                        @error('total_amount')
                                        <span class="text-danger" role="alert">
                                            <p>{{ $message }}</p>
                                        </span>
                                        @enderror

                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea name="description" id="description" class="form-control" cols="2" rows="2" placeholder="Write description">{!! old('description') !!}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-success mr-2">Update</button>
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
    <script src="{{ asset('js/alerts.js')}}"></script>
    <script src="{{ asset('plugins/sweetalert/dist/sweetalert.min.js') }}"></script>
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
                <div class="col-sm-3">
                        <div class="form-group">
                            <label for="ingredient_id">Ingredient Name<span class="text-red">*</span></label>
                            <input type="text" id="ingredient${i}" value="${data.name}" class="form-control" readonly>
                            <input type="hidden" id="ingredient${i}" name="ingredient_id[]" value="${data.id}" class="form-control">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="quantity">Quantity(${data.units.unit_name})<span class="text-red">*</span></label>

                            <input type="hidden" name="unit_value[]" value="${data.units.unit_value}" >

                            <input type="text" onkeyup="Quantity(${i})" name="quantity[]" id="quantity${i}" value="{{ old('quantity') }}" class="form-control quantity @error('quantity') is-invalid @enderror" placeholder="Enter quantity" required>

                            @error('quantity')
                            <span class="text-danger" role="alert">
                                <p>{{ $message }}</p>
                            </span>
                            @enderror

                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="unit_price">Unit Price<span class="text-red">*</span></label>

                            <input type="text" onkeyup="UnitPrice(${i})" name="unit_price[]" id="unit_price${i}" value="{{ old('unit_price') }}" class="form-control unit_price @error('unit_price') is-invalid @enderror" placeholder="Enter unit price" required>

                            @error('unit_price')
                            <span class="text-danger" role="alert">
                                <p>{{ $message }}</p>
                            </span>
                            @enderror

                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="amount">Amount<span class="text-red">*</span></label>
                            <div class="d-flex">
                                <input type="text" name="amount[]" id="amount${i}" value="{{ old('amount') }}" class="form-control amount @error('amount') is-invalid @enderror" placeholder="Enter unit price" readonly>

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

            var sum = 0;
            $(".quantity").each(function(){
                sum += parseFloat(this.value);
            });
            $("#total_quantity").val(sum);

            var totalAmount = 0;
            $(".amount").each(function(){
                totalAmount += parseFloat(this.value);
            });
            $("#total_amount").val(totalAmount);
    });

        //calculation

        $(document).on("input", ".quantity", function() {
            var sum = 0;
            $(".quantity").each(function(){
                sum += parseFloat(this.value);
            });
            $("#total_quantity").val(sum);
        });

          function UnitPrice(id){
            var amount = parseFloat($('#amount'+id).val());
            var unitPrice = parseFloat($('#unit_price'+id).val());
            var quantity = parseFloat($('#quantity'+id).val());

            var preAmount = parseFloat($('#preAmount'+id).val());
            var preUnitPrice = parseFloat($('#preUnitPrice'+id).val());
            var preQuantity = parseFloat($('#preQuantity'+id).val());


            var total = quantity * unitPrice;
            $('#amount'+id).val(total);

            var sum = 0;
            $(".amount").each(function(){
                sum += parseFloat(this.value);
            });
            $("#total_amount").val(sum);

        }

          function PreUnitPrice(id){
            var amount = parseFloat($('#preAmount'+id).val());
            var unitPrice = parseFloat($('#preUnitPrice'+id).val());
            var quantity = parseFloat($('#preQuantity'+id).val());

            var total = quantity * unitPrice;
            $('#preAmount'+id).val(total);

            var sum = 0;
            $(".amount").each(function(){
                sum += parseFloat(this.value);
            });
            $("#total_amount").val(sum);

        }

          function Quantity(id){
            var amount = parseFloat($('#amount'+id).val());
            var unitPrice = parseFloat($('#unit_price'+id).val());
            var quantity = parseFloat($('#quantity'+id).val());

            var total = quantity * unitPrice;
            $('#amount'+id).val(total);

            var sum = 0;
            $(".amount").each(function(){
                sum += parseFloat(this.value);
            });
            $("#total_amount").val(sum);
        }

        function PreQuantity(id){
        var amount = parseFloat($('#preAmount'+id).val());
        var unitPrice = parseFloat($('#preUnitPrice'+id).val());
        var quantity = parseFloat($('#preQuantity'+id).val());

        var total = quantity * unitPrice;
        $('#preAmount'+id).val(total);

        var sum = 0;
        $(".amount").each(function(){
            sum += parseFloat(this.value);
        });
        $("#total_amount").val(sum);
    }

        $(document).on('click', '.row_remove', function(e) {
            e.preventDefault();
                swal({
                    title: `Are you sure?`,
                    text: `Want to remove this?`,
                    buttons: true,
                    dangerMode: true,
                }).then((data) => {
                    if (data == true) {
                    $(this).parents('.row_del').remove();
                    $('select[name*="ingredient_id"] option').attr('disabled',false);
                    // console.log($(this).parents('.row_del').$('#previous_ingredient'));

                    var sum = 0;
                    $(".quantity").each(function(){
                        sum += parseFloat(this.value);
                    });
                    $("#total_quantity").val(sum);

                    var TotalAmount = 0;
                    $(".amount").each(function(){
                        TotalAmount += parseFloat(this.value);
                    });
                    $("#total_amount").val(TotalAmount);
                }
                else {
                    return false;
                }
            });
         })
</script>
@endpush
@endsection
