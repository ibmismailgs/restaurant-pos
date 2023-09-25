@extends('layouts.main')
@section('title', 'Add Sales')
@section('content')
@push('head')
    <link rel="stylesheet" href="{{ asset('plugins/select2/dist/css/select2.min.css') }}">
@endpush

    <div class="container-fluid">
    	<div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-user-plus bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Add Sales')}}</h5>

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
                        <h3>{{ __('Add Sales')}}</h3>
                    </div>
                    <div class="card-body">
                        <form enctype="multipart/form-data" action="{{ route('sales.store') }}" method="POST">
                            @csrf
                            <div class="row">

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="order_no">Order No<span class="text-red">*</span></label>

                                        <input type="text" name="order_no" id="order_no" value="{{ $orderNo + 1 }}" class="form-control" readonly>

                                    </div>
                                </div>

                                <div class="col-sm-3">
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
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="customer_name">Customer Name</label>

                                        <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name') }}" class="form-control" placeholder="Enter customer name" >

                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="customer_mobile">Customer Mobile</label>

                                        <input type="text" name="customer_mobile" id="customer_name" value="{{ old('customer_name') }}" class="form-control" placeholder="Enter customer name">

                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="product_id">Product<span class="text-red">*</span></label>

                                        <select id="product_id" class="form-control select2     @error('product_id') is-invalid @enderror">
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

                            </div>

                            <div  id="AddField">

                            </div>

                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="total_quantity">Total Quantity<span class="text-red">*</span></label>

                                        <input type="text" name="total_quantity" id="total_quantity" value="{{ old('total_quantity') }}" class="form-control @error('total_quantity') is-invalid @enderror" placeholder="total quantity" readonly>

                                        @error('total_quantity')
                                        <span class="text-danger" role="alert">
                                            <p>{{ $message }}</p>
                                        </span>
                                        @enderror

                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="total_amount">Total Amount<span class="text-red">*</span></label>

                                        <input type="number" name="total_amount" id="total_amount" value="{{ old('total_amount') }}" class="form-control @error('total_amount') is-invalid @enderror" placeholder="total amount" readonly>

                                        @error('total_amount')
                                        <span class="text-danger" role="alert">
                                            <p>{{ $message }}</p>
                                        </span>
                                        @enderror

                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="discount">Discount Amount</label>
                                        <input type="number" oninput="Discount()" name="discount" id="discount" value="{{ old('discount') }}" class="form-control discount" placeholder="discount amount">
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="discount">Grand Total</label>
                                        <input type="number" name="grand_total" id="grand_total" value="{{ old('grand_total') }}" class="form-control grand_total" readonly>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="tax">Vat(%)</label>
                                        <input type="number" onchange="Tax()" name="tax_amount" id="tax" value="{{ old('tax') }}" class="form-control tax" placeholder="vat amount">
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="payment_type">Payment Type<span class="text-red">*</span></label>
                                        <select name="payment_type" id="payment_type" class="form-control select2 @error('payment_type') is-invalid @enderror" required>
                                            <option value="">Select Type</option>
                                            <option value="1">Cash</option>
                                            <option value="2">Card</option>
                                        </select>
                                        @error('payment_type')
                                        <span class="text-danger" role="alert">
                                            <p>{{ $message }}</p>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-sm-3" id="transaction_id">
                                    <div class="form-group">
                                        <label for="mobile_transaction_id">Transaction ID<span class="text-red">*</span></label>

                                        <input type="text" name="transaction_id" value="{{ old('transaction_id') }}"class="form-control @error('transaction_id') is-invalid @enderror" placeholder="Enter transaction id">

                                        @error('mobile_transaction_id')
                                        <span class="text-danger" role="alert">
                                            <p>{{ $message }}</p>
                                        </span>
                                        @enderror

                                    </div>
                                </div>

                                <div class="col-sm-3" >
                                    <div class="form-group">
                                        <label for="paying_amount">Paying Amount</label>

                                        <input type="text" name="paying_amount" id="paying_amount" value="{{ old('paying_amount') }}" class="form-control @error('paying_amount') is-invalid @enderror" placeholder="Enter paying amount">

                                    </div>
                                </div>

                                <div class="col-sm-3" >
                                    <div class="form-group">
                                        <label for="change_amount">Change </label>

                                        <input type="text" name="change_amount" id="change_amount" value="{{ old('change_amount') }}"class="form-control @error('change_amount') is-invalid @enderror" placeholder="Enter change amount" readonly>

                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="note">Note</label>
                                        <textarea name="note" id="note" class="form-control" cols="2" rows="2" placeholder="Write note">{!! old('note') !!}</textarea>
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
    <script src="{{ asset('js/alerts.js')}}"></script>
    <script src="{{ asset('plugins/sweetalert/dist/sweetalert.min.js') }}"></script>
    <script>
        var i = 0 ;
        var removedItems = [];
        $('#product_id').on('change', function(e){
                e.preventDefault();
                var product_id = $("#product_id").val();
                var selectedOption = $(this).find(":selected");
                selectedOption.remove();
                removedItems.push(selectedOption);

                var url = "{{ route('get-products') }}";
                $.ajax({
                    type: "get",
                    url: url,
                    data: {
                        product_id: product_id,
                    },

                    success: function(data) {
                    i++;

                    if(data.recipe == null){
                        swal({
                            title: 'Error!!!',
                            text: "Please create recipe first",
                            dangerMode: true,
                        });
                    }else{
                        $("#AddField").append(`<div class="row" id="removed">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="product_id">Product Name<span class="text-red">*</span></label>
                                <input type="text" id="product${i}" value="${data.product.name}" class="form-control" readonly>
                                <input type="hidden" id="product${i}" name="product_id[]" value="${data.product.id}" class="form-control">
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="product_id">Price<span class="text-red">*</span></label>
                                <input type="text" name="price[]" id="price${i}" value="${data.product.price}" class="form-control" readonly>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="quantity">Quantity<span class="text-red">*</span></label>
                                    <input type="number" onkeyup="Quantity(${i})" name="quantity[]" id="quantity${i}" value="{{ old('quantity') }}" class="form-control quantity @error('quantity') is-invalid @enderror" placeholder="Enter quantity" required>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="product_id">Sub Total<span class="text-red">*</span></label>
                                <div class="d-flex">
                                    <input type="text" name="amount[]" id="amount${i}" value="${data.product.price}" class="form-control amount" readonly>

                                    <button title="Remove Button" style="margin-left: 5px; height:35px; font-size:14px; text-align:center" type="button" name="del" id="del" class="btn btn-danger btn_remove">-</button>
                                </div>
                            </div>
                        </div>

                    </div>`);
                    }

                    }
                });
            })

            $(document).on('click', '.btn_remove', function() {
                $(this).parents('#removed').remove();
                var lastRemovedOption = removedItems.pop();
                $("#product_id").append(lastRemovedOption);

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

            $(document).on("input", ".quantity", function() {
            var sum = 0;
            $(".quantity").each(function(){
                sum += parseFloat(this.value);
            });
            $("#total_quantity").val(sum);

            var amount = parseFloat($('#total_amount').val());
            var discount =  parseFloat($('#discount').val());
            sum = amount - discount;
            $("#total_amount").val(sum);

        });

        function Quantity(id){
            var amount = parseFloat($('#amount'+id).val());
            var unitPrice = parseFloat($('#price'+id).val());
            var quantity = parseFloat($('#quantity'+id).val());

            var total = quantity * unitPrice;
            $('#amount'+id).val(total);

            var sum = 0;
            $(".amount").each(function(){
                sum += parseFloat(this.value);
            });
            $("#total_amount").val(sum);
            $("#grand_total").val(sum);
            $("#pre_grand_total").val(sum);
        }

        function Discount(){
            var amount = parseFloat($('#total_amount').val());
            var discount =  parseFloat($('#discount').val());
            if(discount){
                sum = amount - discount;
                $("#grand_total").val(sum);
            }else{
                $("#grand_total").val(amount);
            }
        }

            function Tax(){
                var grandTotal = parseFloat($('#grand_total').val());
                var totalAmount = parseFloat($('#total_amount').val());
                var tax = parseFloat($('#tax').val());
                var discount = parseFloat($('#discount').val());

                if(tax){
                    var taxAmount = (grandTotal * tax) / 100 ;
                    var total = grandTotal + taxAmount;
                    $("#grand_total").val(total);
                }else if(tax == ' '){
                    sum = totalAmount - discount;
                    $("#grand_total").val(sum);
                }else{
                    $("#grand_total").val(totalAmount);
                }
            }

        $("#transaction_id").hide();

        $("#payment_type").on('change', function(){
            if ($(this).val() == 1) {
                $("#transaction_id").hide();
            }else if($(this).val() == 2){
                $("#transaction_id").show();
            }else{
                $("#transaction_id").hide();
            }
        });

        $("#paying_amount").on('input', function(){
            var grand_total = parseFloat($('#grand_total').val());
            var paying_amount = parseFloat($('#paying_amount').val());

            var result = paying_amount - grand_total;
            $('#change_amount').val(result)
        });

    </script>
    @endpush
    @endsection

