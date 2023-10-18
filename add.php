@extends('admin.layouts.main')

@section('title', 'Create Order')

@section('content')


{{---
    @foreach(generateCombinationsForProduct($product->id) as $pro) 
                                            <option product_price="{{ $product->base_price }}" value="{{ $product->id }}">{{ $product->name }} {{ getOptionName($pro) }}</option>
@endforeach---}}

<style>
    html,
    body {
        overflow-x: hidden;
    }
</style>

<h4 class="py-3 mb-2"> Create Order</span></h4>

<p><a href="{{ route('admin.products.index') }}"><i class='bx bx-arrow-back'></i> Back</a></p>


<!-- Organize Card -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">Organize</h5>
    </div>
    <div class="card-body">

        <div class="mb-3">

            <div class="row">

                @foreach(\App\Models\Category::parentCategory() as $category)
                <div class="col-md mb-md-0 mb-2">
                    <div class="form-check custom-option custom-option-basic">
                        <label class="form-check-label custom-option-content" for="customRadioTemp{{$category->id}}">
                            <input name="product_type" onclick="window.location=window.location.href.split('?')[0]+'?product_type={{$category->id}}';" class="form-check-input" type="radio" value="{{ $category->id }}" @if(isset($_GET['product_type']) && $_GET['product_type']==$category->id) checked="" @endif id="customRadioTemp{{$category->id}}" />
                            <span class="custom-option-header pb-0">
                                <span class="h6 mb-0">{{ $category->name }}</span>
                                <img src="{{ url('storage/category/thumbnail/'.$category->thumbnail) }}" width="30px" class="float-end">
                            </span>
                        </label>
                    </div>
                </div>
                @endforeach

            </div>


        </div>
        @if(isset($_GET['product_type']))
        <div>

            <form method="POST" action="{{ route('admin.orders.store') }}" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="product_type" value="{{ $_GET['product_type'] }}">

                <div class="mb-3">
                    <label class="form-label">User</label>
                    <select name="users" id="users" class="select2 form-select" required data-placeholder="Select">
                        <option value="">Select</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->firstname .' '. $user->lastname }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="attributes_options mb-3">
                    <div class="form-repeater">
                        <div data-repeater-list="products_grp">

                            <div data-repeater-item>
                                <div class="product-group">

                                    <div class="mb-3">
                                        <label class="form-label" for="form-repeater-1-15">Add Products</label>
                                        <select name="product_id" onchange="generateAtt(event)" id="form-repeater-1-15" required class="products select2 form-select" data-placeholder="Select">
                                            <option value="">Select</option>
                                            @foreach($products as $product)
                                            <option product_price="{{ $product->base_price }}" value="{{ $product->id }}">{{ $product->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="row">
                                        @foreach($attributes as $key => $value)
                                        <div class="attributes col-2">
                                            <div class="mb-3">
                                                <label class="form-label" for="form-repeater-1-15{{$value->id}}">{{ $value->name }}</label>
                                                <select id="attribute{{$value->id}}" class="attribute_options_{{ $value->id }} options select2 form-select" name="{{$value->id}}" data-placeholder="Select">

                                                    <option value="">Select</option>
                                                </select>
                                            </div>
                                        </div>
                                        @endforeach

                                        <div class="mb-3 col-2 d-flex">
                                            <div>
                                                <label class="form-label" for="form-repeater-3-1">Price Addon</label>
                                                <input required min="0" type="number" id="form-repeater-3-1" name="price" class="price form-control" />
                                            </div>
                                            <!-- <label class="form-label" for="form-repeater-3-1">Price Addon</label> -->
                                            <button type="button" class="btn btn-label-danger mt-4 ms-2 float-end" data-repeater-delete>
                                                <i class="bx bx-x"></i>
                                                <!-- <span class="align-middle">Delete</span> -->
                                            </button>
                                        </div>
                                    </div>

                                    <hr>
                                </div>
                            </div>

                        </div>
                        <div class="float-end">
                            <!-- <p>Sub Total : <span id="sub-total"></span></p> -->
                        </div>
                        <div>
                            <button class="btn btn-primary repeater" onclick="disableOptions()" type="button" data-repeater-create="">Add Products</button>
                        </div>
                    </div>
                </div>

                <div id="address_div">

                    <div class="mb-3">
                        <label class="form-label">Delivery Address</label>
                        <a data-bs-toggle="modal" data-bs-target="#addNewAddress" class="float-end mt-2"> <i class="bx bx-plus"></i> Add Address</a>
                        <select name="delivery_address" required id="delivery_address" class="addresses select2 form-select" data-placeholder="Select">
                            <option value="">Select</option>
                        </select>
                    </div>


                    <div class="mb-3">
                        <label class="form-label">Billing Address</label>
                        <select name="billing_address" required id="billing_address" class="addresses select2 form-select" data-placeholder="Select">
                            <option value="">Select</option>
                        </select>
                    </div>

                </div>


                <div class="row">

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Order Status</label>
                            <select name="order_status" id="order_status" class="select2 form-select" data-placeholder="Select">
                                <option value="">Select</option>
                                <option value="Pending">Pending</option>
                                <option value="Cancelled">Cancelled</option>
                                <option value="Success">Success</option>
                            </select>
                        </div>
                    </div>


                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Payment Status</label>
                            <select name="payment_status" id="payment_status" class="select2 form-select" data-placeholder="Select">
                                <option value="">Select</option>
                                <option value="Paid">Paid</option>
                                <option value="Unpaid">Unpaid</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Payment Type</label>
                            <select name="payment_type" id="payment_type" class="select2 form-select" data-placeholder="Select">
                                <option value="">Select</option>
                                <option value="COD">Cash on Delivery</option>
                                <option value="Card">Card</option>
                            </select>
                        </div>
                    </div>

                </div>

                <div class="my-3">
                    <button class="btn btn-primary float-end">Create Order</button>
                </div>
            </form>
        </div>
        @endif
    </div>
</div>
<!-- /Organize Card -->

<!-- Add New Address Modal -->
<div class="modal fade" id="addNewAddress" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-add-new-address">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h3 class="address-title">Add New Address</h3>
                    <p class="address-subtitle">Add new address for express delivery</p>
                    <div id="validation-errors"></div>
                </div>
                <form id="addNewAddressForm" class="row g-3" action="{{ route('admin.user.address.create') }}" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" value="" id="modal_user_id">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-md mb-md-0 mb-3">
                                <div class="form-check custom-option custom-option-icon">
                                    <label class="form-check-label custom-option-content" for="customRadioHome">
                                        <span class="custom-option-body">
                                            <i class="bx bx-home"></i>
                                            <span class="custom-option-title">Home</span>
                                            <small> Delivery time (9am - 9pm) </small>
                                        </span>
                                        <input name="customRadioIcon" class="form-check-input" type="radio" value="Home" id="customRadioHome" checked />
                                    </label>
                                </div>
                            </div>
                            <div class="col-md mb-md-0 mb-3">
                                <div class="form-check custom-option custom-option-icon">
                                    <label class="form-check-label custom-option-content" for="customRadioOffice">
                                        <span class="custom-option-body">
                                            <i class='bx bx-briefcase'></i>
                                            <span class="custom-option-title"> Office </span>
                                            <small> Delivery time (9am - 5pm) </small>
                                        </span>
                                        <input name="customRadioIcon" class="form-check-input" type="radio" value="Office" id="customRadioOffice" />
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="AddressFirstName">First Name</label>
                        <input type="text" id="AddressFirstName" required name="AddressFirstName" class="form-control" placeholder="John" value="{{ old('AddressFirstName') }}" />
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="AddressLastName">Last Name</label>
                        <input type="text" id="AddressLastName" required name="AddressLastName" class="form-control" placeholder="Doe" value="{{ old('AddressLastName') }}" />
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="AddressCountry">Country</label>
                        <select id="modalAddressCountry" name="AddressCountry" required class="select2 form-select" data-allow-clear="true" value="{{ old('AddressCountry') }}">
                            <option value="">Select</option>
                            <option value="Australia">Australia</option>
                            <option value="Bangladesh">Bangladesh</option>
                            <option value="Belarus">Belarus</option>
                            <option value="Brazil">Brazil</option>
                            <option value="Canada">Canada</option>
                            <option value="China">China</option>
                            <option value="France">France</option>
                            <option value="Germany">Germany</option>
                            <option value="India">India</option>
                            <option value="Indonesia">Indonesia</option>
                            <option value="Israel">Israel</option>
                            <option value="Italy">Italy</option>
                            <option value="Japan">Japan</option>
                            <option value="Korea">Korea, Republic of</option>
                            <option value="Mexico">Mexico</option>
                            <option value="Philippines">Philippines</option>
                            <option value="Russia">Russian Federation</option>
                            <option value="South Africa">South Africa</option>
                            <option value="Thailand">Thailand</option>
                            <option value="Turkey">Turkey</option>
                            <option value="Ukraine">Ukraine</option>
                            <option value="United Arab Emirates">United Arab Emirates</option>
                            <option value="United Kingdom">United Kingdom</option>
                            <option value="United States">United States</option>
                        </select>
                    </div>
                    <div class="col-12 ">
                        <label class="form-label" for="AddressAddress1">Address Line 1</label>
                        <input type="text" value="{{ old('AddressAddress1') }}" id="modalAddressAddress1" name="AddressAddress1" required class="form-control" placeholder="12, Business Park" />
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="AddressAddress2">Address Line 2</label>
                        <input type="text" value="{{ old('AddressAddress2') }}" id="modalAddressAddress2" name="AddressAddress2" required class="form-control" placeholder="Mall Road" />
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="AddressLandmark">Landmark</label>
                        <input type="text" value="{{ old('AddressLandmark') }}" id="modalAddressLandmark" name="AddressLandmark" required class="form-control" placeholder="Nr. Hard Rock Cafe" />
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="AddressCity">City</label>
                        <input type="text" value="{{ old('AddressCity') }}" id="modalAddressCity" name="AddressCity" required class="form-control" placeholder="Los Angeles" />
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="AddressLandmark">State</label>
                        <input type="text" value="{{ old('AddressLandmark') }}" id="modalAddressState" name="AddressState" required class="form-control" placeholder="California" />
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="AddressZipCode">Zip Code</label>
                        <input type="text" value="{{ old('AddressZipCode') }}" id="modalAddressZipCode" name="AddressZipCode" required class="form-control" placeholder="99950" />
                    </div>
                    <div class="col-12">
                        <label class="switch">
                            <input type="checkbox" class="switch-input" value="yes" name="is_billing">
                            <span class="switch-toggle-slider">
                                <span class="switch-on"></span>
                                <span class="switch-off"></span>
                            </span>
                            <span class="switch-label">Use as a billing address?</span>
                        </label>
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@push('script')
<script src="/admin-resources/assets/js/orders.js"></script>


<script>
    function disableOptions() {
                $('option').prop('disabled', false)
        setTimeout(() => {
            $('body').find('.products').each((key, value) => {
                const selected_prod_id = value.value

                if (selected_prod_id) {
                    $('body').find('.products').each((key1, value1) => {
                        if(value1.id != value.id){
                            $(value1).find('option').each((key2, value2)=>{
                                if(value2.value == selected_prod_id){
                                    $(value2).prop('disabled', true)
                                }
                                console.log(value2.value, selected_prod_id)
                            })
                        }
                    })
                }

            })
        }, 1000);
    }

    function generateAtt(event) {
        const product_id = event.target.value

        const select = $(event.target).closest('.product-group')


        ///fetching product attributes
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ route('admin.product.get.attributes') }}",
            type: 'POST',
            data: {
                id: product_id
            },
            success: (data) => {
                if (data.data) {
                    // console.log(data.data)
                    $(select).find('select.options').each((key, value) => {
                        // console.log(value)
                        $(value).html("")
                    })
                    $.each(data.data, function(key, value) {
                        $(select).find(".attribute_options_" + key).html("")
                        $.each(value, function(key1, value1) {
                            $(select).find(".attribute_options_" + value1.attribute_id).append('<option attribute_id="' + value1.attribute_id + '" value="' + value1.attribute_options_id + '">' + value1.attribute_option.value + '</option>');
                        })
                    });
                }
            }
        });

        disableOptions()


        // console.log(product_id)
        // select.each((key,value)=>{
        //     $(value).val("")
        // })
        // console.log($(event.target).closest('.product-group'))
    }
</script>

<script>
    $("#address_div").hide()
</script>
<script>
    $(document).ready(function() {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/admin/order/get-user-address/" + document.getElementById('users').value,
            type: 'GET',
            success: function(data) {
                $(".addresses").empty();
                if (data) {
                    $("#address_div").show()
                    console.log(data.address)
                    $.each(data.address, function(key, value) {
                        $(".addresses").append('<option value="' + value.id + '">' + value.first_name + ' ' + value.first_name + ' | ' + value.address_line_one + ', ' + value.address_line_two + ', ' + value.city + ', ' + value.state + ', ' + value.country + ', ' + value.zipcode + '</option>');
                    });
                }
            }
        });
    })
</script>

<script>
    $("#users").change(function(event) {
        $('#modal_user_id').val(event.target.value)
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/admin/order/get-user-address/" + event.target.value,
            type: 'GET',
            success: function(data) {
                $(".addresses").empty();
                if (data) {
                    $("#address_div").show()
                    console.log(data.address)
                    $.each(data.address, function(key, value) {
                        $(".addresses").append('<option value="' + value.id + '">' + value.first_name + ' ' + value.first_name + ' | ' + value.address_line_one + ', ' + value.address_line_two + ', ' + value.city + ', ' + value.state + ', ' + value.country + ', ' + value.zipcode + '</option>');
                    });
                }
            }
        });
    })
</script>



<script>
    $('#addNewAddressForm').on('submit', function(e) {
        e.preventDefault();

        if (!$('#modal_user_id').val()) {
            alert('please select a user')
            return
        }

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/admin/order/store-user-address",
            type: 'POST',
            data: $("#addNewAddressForm").serialize(),

            success: function(data) {
                // console.log(data)
                if (data.status == 422) {
                    $('#addNewAddress').modal('show');
                    $('#validation-errors').html('');
                    var errors = $.parseJSON(data.responseText);
                    $.each(errors, function(key, value) {
                        $('#validation-errors').append('<div class="alert alert-danger alert-dismissible" role="alert">' + value + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    });
                }

            },
            error: function(xhr) {
                console.log(xhr);
                $('#addNewAddress').modal('show');
                $('#validation-errors').html('');
                $.each(xhr.responseJSON.errors, function(key, value) {
                    $('#validation-errors').append('<div class="alert alert-danger alert-dismissible" role="alert">' + value + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                });
            },

        });

    })
</script>

<!-- //// selecting product type  -->
<script>
    $('input[type=radio]').on('change', function() {
        if (document.querySelector('input[name="product_type"]:checked').value) {
            $(this).closest("form").submit();
        }
    });
</script>





<script>
    $(".form-repeater").change(function(event) {
                if (event.target.classList.contains('products')) {
                    var product_select = event.target;

                    product_select.closest(".product-group").querySelector(".products").value = product_select.value

                    var product_price = product_select.options[product_select.selectedIndex].getAttribute('product_price'; document.getElementById(product_select.closest(".product-group").querySelector(".price").id).value = product_price;


                        ///fetching product attributes
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: "{{ route('admin.product.get.attributes') }}",
                            type: 'POST',
                            data: {
                                id: product_select.value
                            },
                            success: (data) => {
                                if (data.data) {

                                    console.log($(this).parent())

                                    $.each(data.data, function(key, value) {
                                        // $("#"+product_select.closest(".product-group").querySelector(".attribute_options_"+value.attribute_id).id).html('');
                                        $.each(value, function(key1, value1) {
                                            // console.log(product_select.closest(".product-group").querySelector(".attribute_options_"+value1.attribute_id).id)
                                            $("#" + product_select.closest(".product-group").querySelector(".attribute_options_" + value1.attribute_id).id).append('<option attribute_id="' + value1.attribute_id + '" value="' + value1.attribute_options_id + '">' + value1.attribute_option.value + '</option>');
                                        })
                                    });
                                }
                            }
                        });

                    }


                    if (event.target.classList.contains('options')) {
                        var option = event.target

                        var option_id = option.value
                        var attr_id = option.options[option.selectedIndex].getAttribute('attribute_id');
                        var product_id = option.closest(".product-group").querySelector(".products").value

                        if (!product_id) {
                            alert('Please select product')
                            return
                        }

                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: "{{ route('admin.product.get.options') }}",
                            type: 'POST',
                            data: {
                                product_id: product_id,
                                attr_id: attr_id,
                                attr_option_id: option_id
                            },
                            success: function(data) {
                                // console.log(data)
                                // $("#" + nextSelect.id).empty();
                                console.log(option.closest(".product-group").querySelector(".price").value)
                                var total = +option.closest(".product-group").querySelector(".price").value + +data.data.addon_amount
                                // console.log(total)
                                document.getElementById(option.closest(".product-group").querySelector(".price").id).value = total;

                            }

                        });

                    }


                });
</script>


@endpush
@endsection
