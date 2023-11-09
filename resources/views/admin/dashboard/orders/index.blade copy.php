@extends('admin.dashboard.layouts.master')

@section('styles')
    <style>
        .list-styled{
            list-style-type: disc!important;
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
                        <h4 class="card-title">Manage Orders</h4>
                        {{-- <div class="d-sm-flex justify-content-between">
                            <a href="{{ route('admin.orders.create') }}" class="btn-sm btn-success text-white">Add
                                Product</a>
                        </div> --}}
                    </div>
                    <div class="card-body">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table header-border table-responsive-sm">
                                    <thead>
                                        <tr>
                                            <th class="font-weight-bold text-dark">#</th>
                                            <th class="font-weight-bold text-dark">Order Code</th>
                                            <th class="font-weight-bold text-dark">Order Date</th>
                                            <th class="font-weight-bold text-dark">Customer</th>
                                            <th class="font-weight-bold text-dark">Num. of Products</th>
                                            <th class="font-weight-bold text-dark">Products</th>
                                            <th class="font-weight-bold text-dark">Item Amount</th>
                                            <th class="font-weight-bold text-dark">Item Order Status</th>
                                            {{-- <th class="font-weight-bold text-dark">Order Status</th> --}}
                                            <th class="font-weight-bold text-dark">Total Amount</th>
                                            <th class="font-weight-bold text-dark">Actions</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($orders as $key=> $order)

                                            <tr>
                                                <td>{{$key+1}}</td>
                                                <td>{{$order->order_number}}</td>
                                                <td>{{$order->order_date}}</td>
                                                <td>{{$order->user->name}}</td>
                                                {{-- <td>{{$order->quantiy}}</td> --}}
                                                <td>
                                                    <ul class="list-styled">
                                                        @forelse ($order->orderItems as $item)

                                                        <li>

                                                             {{$item->quantity}}

                                                        </li>
                                                        @empty

                                                        @endforelse
                                                    </ul>

                                                </td>
                                                <td>
                                                    <ul class="list-styled">
                                                        @forelse ($order->orderItems as $key=> $item)

                                                        <li><i class="fa fa-arrow-right"></i>

                                                             {{Str::limit($item->product->name,50)}}...

                                                        </li>
                                                        @empty

                                                        @endforelse
                                                    </ul>

                                                </td>

                                              <td>
                                                <ul class="list-styled">
                                                @forelse ($order->orderItems as $key=> $itemAmount)
                                                <li>
                                                    {{$itemAmount->price}}
                                                </li>
                                                @empty
                                                @endforelse
                                                </ul>
                                              </td>


                                                <td>
                                                    <ul class="list-styled">
                                                    @forelse ($order->orderItems as $key=> $item)
                                                    <li>
                                                    @if ($item->delivery_status=="pending")
                                                    <span class="text-warning badge btn-sm">{{$item->delivery_status}}</span>

                                                    @elseif ($item->delivery_status=="processing")
                                                    <span class="text-info badge btn-sm">{{$item->delivery_status}}</span>

                                                    @elseif ($item->delivery_status=="completed")
                                                    <span class="text-success badge btn-sm">{{$item->delivery_status}}</span>

                                                    @else
                                                    <span class="text-danger badge btn-sm">{{$item->delivery_status}}</span>

                                                    @endif
                                                    </li>
                                                    @empty

                                                    @endforelse
                                                    </ul>


                                                </td>

                                                 <td>

                                                    @if ($order->status=="pending")
                                                    <span class="text-warning badge btn-sm">{{$order->status}}</span>

                                                    @elseif ($order->status=="processing")
                                                    <span class="text-info badge btn-sm">{{$order->status}}</span>

                                                    @elseif ($order->status=="completed")
                                                    <span class="text-success badge btn-sm">{{$order->status}}</span>

                                                    @else
                                                    <span class="text-danger badge btn-sm">{{$order->status}}</span>

                                                    @endif




                                                </td>

                                                <td>{{$order->total_amount}}</td>

                                                <td>
                                                    <a class=""
                                                    href="{{ route('admin.orders.edit', $order->id) }}">
                                                    <i class="fa fa-edit text-warning btn"></i>
                                                </a>

                                                </td>
                                            </tr>

                                        @empty

                                            <tr>
                                                <th colspan="8" class="text-center">No Orderd To List</th>
                                            </tr>

                                        @endforelse

                                    </tbody>
                                </table>
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
