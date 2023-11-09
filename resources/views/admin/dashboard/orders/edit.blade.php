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
                    <p class="mb-0">View and manage all orders received through your platform or website.</p>
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
            <div class="card mb-4">
                <div class="card-body">
                    <div class="mb-5 d-flex align-items-center justify-content-between">
                        <span>Order No : <a href="#"> {{$orders->details[0]->order->order_number}}</a></span>
                        <span class="badge badge-info">

                            {{$orders->details[0]->order->status}}
                        </span>
                    </div>
                    <div class="row mb-5 g-4">
                        <div class="col-md-3 col-sm-6">
                            <p class="fw-bold">Order Created at</p>
                            {{$orders->details[0]->order->created_at->format('d-m-Y')}}
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <p class="fw-bold">Name</p>
                            {{$orders->details[0]->order->user->name}}
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <p class="fw-bold">Email</p>
                            {{$orders->details[0]->order->user->email}}
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <p class="fw-bold">Contact No</p>
                            {{$orders->details[0]->order->user->phone}}
                        </div>
                    </div>
                    <div class="row g-4">

                        @forelse ($orders->details[0]->order->user->address as $key=> $userAddress)
                        <div class="col-md-3 col-sm-12">
                            <div class="card">
                                <div class="card-body d-flex flex-column gap-1">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="mb-0">Address {{$key+1}}</h5>
                                        {{-- <a href="{{route('admin.customers.index')}}" class="badge badge-warning">Edit</a> --}}
                                    </div>
                                    <div>{{$userAddress->address}}</div>
                                    <div>{{$userAddress->locality}}</div>
                                    <div>
                                        {{$userAddress->city}},
                                        {{$userAddress->landmark}},
                                        {{$userAddress->state->name}},
                                        {{$userAddress->phone_2}},

                                    </div>
                                    <div>
                                        <i class="bi bi-telephone me-2"></i>  {{$userAddress->phone_1}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty

                        @endforelse

                        {{-- <div class="col-md-6 col-sm-12">
                            <div class="card">
                                <div class="card-body d-flex flex-column gap-3">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="mb-0">Billing Address</h5>
                                        <a href="#">Edit</a>
                                    </div>
                                    <div>Name: Workplace</div>
                                    <div>Josephin Villa</div>
                                    <div>29543 South Plaza, Canada/Sydney Mines</div>
                                    <div>
                                        <i class="bi bi-telephone me-2"></i> 484-948-8535
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
</div>



        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Order Details</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <form action="{{ route('admin.orders.update',$orders->details[0]->order->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                            <table class="table header-border table-responsive-sm">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>
                                                <input type="checkbox" id="select-all">
                                            </th>
                                            <th>Order Item Status</th>
                                            <th>Image</th>
                                            <th>Item</th>
                                            <th>Item Stock</th>
                                            <th>Quantity</th>
                                            <th>Unit Price</th>
                                            <th>Sub Total</th>
                                            {{-- <th>Status</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @forelse ($orders->details as $i => $orderDetail)
                                                    <tr>
                                                        <td>{{$i+1}}</td>
                                                        <td>
                                                            <input type="checkbox"
                                                             name="selectedOrderIds[]"
                                                             value="{{ $orderDetail->id }}">

                                                        </td>
                                                        <td>
                                                            <select  name="delivery_statuses[]" class="form-control">
                                                                <option value="pending"   {{ (old('delivery_status.' . $i)== 'pending' || $orderDetail->status == 'pending') ? 'selected' : '' }}>Pending</option>
                                                                <option value="processing"{{ (old('delivery_status.' . $i)== 'processing'|| $orderDetail->status == 'processing') ?'selected':''}}> Processing</option>
                                                                <option value="completed" {{ (old('delivery_status.' . $i)== 'completed' || $orderDetail->status == 'completed') ?'selected':''}}> Completed</option>
                                                                <option value="cancelled" {{ (old('delivery_status.' . $i)== 'cancelled' || $orderDetail->status == 'cancelled') ?'selected':''}}> Cancelled</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                            @empty


                                            @endforelse

                                    </tbody>
                            </table>
                            <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('scripts')
    <script>
document.addEventListener("DOMContentLoaded", function() {
        var selectAllCheckbox = document.getElementById("select-all");
        var checkboxes = document.querySelectorAll('input[name="selectedOrderIds[]"]');

        selectAllCheckbox.addEventListener("change", function() {
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = selectAllCheckbox.checked;
            });
        });

        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener("change", function() {
                var allChecked = true;
                checkboxes.forEach(function(checkbox) {
                    if (!checkbox.checked) {
                        allChecked = false;
                    }
                });
                selectAllCheckbox.checked = allChecked;
            });
        });
    });
    </script>
@endsection
