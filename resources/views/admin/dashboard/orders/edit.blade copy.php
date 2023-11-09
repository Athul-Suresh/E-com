@extends('admin.dashboard.layouts.master')

@section('styles')
    <style>
.card-img{
    width: 100px;
}
    </style>
@endsection

@section('admin-content')


    <div class="container-fluid">
        <div class="row page-titles mx-0">

            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>All Orders</h4>
                    <p class="mb-0">Lorem ipsum dolor sit amet consectetur adipisicing elit. Nulla, temporibus.</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Admin</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Orders</a></li>
                </ol>
            </div>


        </div>

        @if (Session::has("success"))
        <div class="alert alert-success text-white  alert-dismissible fade show" role="alert">
         {{ Session::get('success') }}
        </div>
        @endif

        @if (Session::has("error"))
        <div class="alert alert-danger text-white  alert-dismissible fade show" role="alert">
         {{ Session::get('error') }}
        </div>
        @endif




        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Order</h4>
                    </div>
                    <div class="card-body">
                        <div class="card-body">
                            @if ($errors->any())
                            {{-- <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div> --}}
                            @endif

                                    <div class="card">
                                        <div class="row">


                                            <div class="col-xl-3 col-md-6 mb-30">
                                                <div class="invoice payment-details mt-5 mt-xl-0">
                                                    <div class="invoice-title c4 bold font-14 mb-3 black h4">Order Details:</div>
                                                    <ul class="status-list">
                                                        <li>
                                                            <span class="key">Order Id: </span> <span class="black font-17 black bold">{{$order->order_number}}</span>
                                                        </li>
                                                        <li>
                                                            <span class="key">Order Date: </span><span class="black">{{$order->order_date}}</span>
                                                        </li>
                                                        <li>
                                                            <span class="key">Total Amount: </span><span class="black"> &#8377;  {{$order->total_amount}}</span>
                                                        </li>
                                                        <li>
                                                            <span class="key">Paid by: </span>
                                                            <span class="text-success"> {{$order->payment}} </span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>



                                            <!--Billing info-->
                                                <div class="col-xl-3 col-md-6 mb-30">
                                                    <!-- Invoice Form -->
                                                    <div class="invoice invoice-form">
                                                        <div class="invoice-title c4 bold font-14 mb-3 h4">Billing Info</div>

                                                        <ul class="list-invoice">
                                                            <li class="bold black font-17">{{$order->user->name}}</li>
                                                            <li class="call">
                                                                <a href="tel:{{$order->user->phone}}" class="text-dark">
                                                                    {{$order->user->phone}}
                                                                </a>
                                                            </li>
                                                            <li class="location">
                                                                {{$order->deliveryAddress->address}},
                                                                {{$order->deliveryAddress->locality}}, <br>
                                                                {{$order->deliveryAddress->city}},
                                                                {{$order->deliveryAddress->state->name}}, <br>
                                                                {{$order->deliveryAddress->pincode}},
                                                                {{$order->deliveryAddress->landmark}},   <br>

                                                            </li>

                                                        </ul>
                                                    </div>
                                                    <!-- End Invoice Form -->
                                                </div>
                                                                <!--End Billing info-->
                                            <!-- Customer info -->
                                            <div class="col-xl-3 col-md-6 mb-30">
                                                <div class="invoice payment-details mt-5 mt-xl-0">
                                                    <div class="invoice-title c4 bold font-14 mb-3 h4">Customer</div>
                                                                                    <ul class="status-list">
                                                            <li>
                                                                <span>{{$order->user->name}}</span>
                                                            </li>

                                                            <li>
                                                                <span class="key">Email: </span>
                                                                <span class="black">{{$order->user->email}}</span>
                                                            </li>
                                                            <li>
                                                                <span class="key">Phone: </span>
                                                                <span class="black">
                                                                    {{$order->user->phone?:'-----'}}
                                                                </span>
                                                            </li>
                                                        </ul>
                                                                            </div>
                                            </div>
                                            <!-- End customer info -->
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="row">







                                            <form
                                            action="{{route('admin.orders.update',$order->id)}}"
                                            method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')

                                                <table class="table">

                                                        <thead>
                                                            <tr>
                                                                <th class="font-weight-bold text-dark">#</th>
                                                                <th class="font-weight-bold text-dark">Order Number</th>
                                                                <th class="font-weight-bold text-dark">Delivery Status</th>
                                                                <th class="font-weight-bold text-dark">Order Quantity</th>
                                                                <th class="font-weight-bold text-dark">Product Stock</th>
                                                                <th class="font-weight-bold text-dark">Product Image</th>
                                                                <th class="font-weight-bold text-dark">Product Name</th>
                                                                {{-- <th class="font-weight-bold text-dark">Category</th>
                                                                <th class="font-weight-bold text-dark">Info</th>
                                                                <th class="font-weight-bold text-dark">Stock & Sales</th>
                                                                <th class="font-weight-bold text-dark">Featured</th>
                                                                <th class="font-weight-bold text-dark">Published</th>
                                                                <th class="font-weight-bold text-dark">Actions</th> --}}
                                                            </tr>
                                                        </thead>
                                                        @forelse ($order->orderItems as $index=> $item)
                                                        <tbody>
                                                            <td>
                                                                <input type="checkbox" name="selected_items[]" value="{{$item->id}}" id="">

                                                                @if ($errors->any())

                                                                        @foreach ($errors->get('selected_items') as $error)
                                                                       <span class="text-danger"> {{$error}}</span>
                                                                        {{-- console.log("{{ $error[0] }}"); --}}
                                                                        {{-- toastr.error("{{ $error[0] }}", "Validation Error"); --}}
                                                                        @endforeach


                                                                @endif
                                                            </td>

                                                            <td>{{$order->order_number}}</td>
                                                            <td><select  name="delivery_status[]" class="form-control">
                                                                <option value="pending"   {{ (old('delivery_status.' . $index)== 'pending' || $item->delivery_status == 'pending') ? 'selected' : '' }}>Pending</option>
                                                                <option value="processing"{{ (old('delivery_status.' . $index)== 'processing'|| $item->delivery_status == 'processing') ?'selected':''}}> Processing</option>
                                                                <option value="completed" {{ (old('delivery_status.' . $index)== 'completed' || $item->delivery_status == 'completed') ?'selected':''}}> Completed</option>
                                                                <option value="cancelled" {{ (old('delivery_status.' . $index)== 'cancelled' || $item->delivery_status == 'cancelled') ?'selected':''}}> Cancelled</option>
                                                            </select>
                                                            @error('delivery_status')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </td>

                                                            <td>{{$item->quantity}}</td>

                                                            <td>{{$item->product->stock}}</td>

                                                            <td><img class="card-img" src="{{ asset('storage/uploads/product/thumbnail/' . $item->product->thumbnail) }}" alt="Image"></td>
                                                            <td>{{$item->product->name}}</td>
                                                            {{-- <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td> --}}

                                                        </tbody>
                                                        @empty
                                                        <tbody>
                                                            <th colspan="9">No Product To Confirm</th>
                                                        </tbody>

                                                        @endforelse

                                                </table>
                                                <a href="{{route('admin.orders.index')}}" class="btn  btn-danger mt-4 pr-4 pl-4">Back</a>
                                                <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Update Order</button>

                                            </form>





                                        </div>
                                    </div>



                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('scripts')
    <script>

    </script>
@endsection
