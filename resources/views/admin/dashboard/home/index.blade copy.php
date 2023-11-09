@extends('admin.dashboard.layouts.master')



@section('admin-content')

<style>
    .saw-table__header {
padding: 1.25rem 1.25rem .875rem
}

.saw-table__body {
border-bottom: 1px solid #2125291a;
margin-bottom: 1rem
}
</style>
    <div class="container-fluid">

        {{-- <div class="row page-titles mx-0">
        <div class="col-sm-6 p-md-0">
            <div class="welcome-text">
                <h4>Hi, welcome back! </h4>
                <p class="mb-0">Your business dashboard</p>
            </div>
        </div>
        <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
            </ol>
        </div>
    </div> --}}


        <div class="row mx-0 g-3">
            <div class="col-sm-3 p-md-0">
                <div class="card m-2">
                    <div class="card-header">
                        <h4>Total Sells</h4>
                    </div>
                    <div class="card-body">
                        <h3>&#8377;{{$totalSales}}</h3>
                    </div>
                </div>
            </div>

            {{-- <div class="col-sm-3 p-md-0">
                <div class="card m-2">
                    <div class="card-header">
                        <h4>Total Orders</h4>

                    </div>
                    <div class="card-body">
                        <h3>&#8377;500</h3>
                    </div>
                </div>
            </div> --}}

            <div class="col-sm-3 p-md-0">
                <div class="card m-2">
                    <div class="card-header">
                        <h4>Active Users</h4>

                    </div>
                    <div class="card-body">
                            <h3>{{$activeUsers}}</h3>
                    </div>
                </div>
            </div>


        </div>
        <div class="row mx-0 g-3 mt-3">

            <div class="col-lg-6 p-md-0">
                <div class="card m-2">
                    <div class="card-header">
                        <h4>Recent orders</h4>
                    </div>
                    <div class="card-body">
                        <div class="saw-table__body table-responsive sa-widget-table text-nowrap">
                            <table class="table table-hovered">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Status</th>
                                        <th>Customer</th>
                                        <th>Date</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentOrders as $recentOrder)
                                    <tr>
                                        <td><a href="{{route('admin.orders.edit',$recentOrder->id)}}" class="text-reset">{{$recentOrder->order_number}}</a></td>
                                        <td>
                                            <div class="d-flex fs-6">
                                                <div class="badge">{{$recentOrder->status}}</div>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div><a href="{{route('admin.customers.edit',$recentOrder->user->id)}}" class="text-reset">{{$recentOrder->user->name}}</a></div>
                                            </div>
                                        </td>
                                        <td>{{ $recentOrder->created_at->format('d-m-Y') }}</td>
                                        <td>&#8377;{{$recentOrder->total_amount}}</td>
                                    </tr>

                                    @empty
                                        <tr>
                                            <th colspan="6">No Recent Orders</th>
                                        </tr>

                                    @endforelse

                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-lg-6 p-md-0 ">
                <div class="card m-2">
                    <div class="card-header">
                        <h4>Income statistics</h4>
                    </div>
                    <div class="card-body">
                    </div>
                </div>
            </div>


        </div>



    </div>
@endsection
