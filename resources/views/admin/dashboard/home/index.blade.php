@extends('admin.dashboard.layouts.master')

@php
    use Carbon\Carbon;
@endphp

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
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Hi, welcome back! </h4>
                    <p class="mb-0">Your business dashboard</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                    <li class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                </ol>
            </div>
        </div>


        <div class="row mx-0 g-3">
            <div class="col-sm-3 p-md-0">
                <div class="card m-2">
                    <div class="card-header">
                        <h4>Total Customers</h4>
                    </div>
                    <div class="card-body">
                        <h4 class="fw-bold fs-4">{{ $activeUsers }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-sm-3 p-md-0">
                <div class="card m-2">
                    <div class="card-header">
                        <h4>Total Sales</h4>

                    </div>
                    <div class="card-body">
                        <h4 class="fw-bold fs-4">₹{{ $totalSales }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-sm-3 p-md-0">
                <div class="card m-2">
                    <div class="card-header">
                        <h4>Today Orders</h4>

                    </div>
                    <div class="card-body">
                        <h4 class="fw-bold fs-4">{{ $todaysOrders }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-sm-3 p-md-0">
                <div class="card m-2">
                    <div class="card-header">
                        <h4>All Time Sales</h4>

                    </div>
                    <div class="card-body">
                        <h4 class="fw-bold fs-4">{{ $allOrders }}</h4>
                    </div>
                </div>
            </div>


        </div>
        <div class="row mx-0 g-3 mt-3">

            <div class="col-lg-6 p-md-0">
                <div class="card m-2" style="min-height:500px">
                    <div class="card-header">
                        <h4>Orders</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="orderStatusChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 p-md-0 ">
                <div class="card m-2" style="min-height:500px">
                    <div class="card-header">
                        <h4>Payment Type</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="paymentChart"></canvas>
                    </div>
                </div>
            </div>


        </div>

        <div class="row mx-0 g-3 mt-3">

            <div class="col-lg-6 p-md-0">
                <div class="card m-2" style="height:500px; overflow-x:scroll">
                    <div class="card-header">
                        <h4>Recent Orders</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table header-border table-responsive-sm">
                                <thead>
                                    <tr>
                                        <th class="font-weight-bold text-dark">#</th>
                                        <th class="font-weight-bold text-dark">Order Number</th>
                                        <th class="font-weight-bold text-dark">Customer</th>
                                        <th class="font-weight-bold text-dark">Items</th>
                                        <th class="font-weight-bold text-dark">Grand Total</th>
                                        <th class="font-weight-bold text-dark">Date</th>
                                        <th class="font-weight-bold text-dark">Status</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @forelse ($latestOrders as $key=> $e)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $e->order_number }}</td>
                                            <td>{{ $e->user->name }}</td>
                                            <td>{{ $e->total_item }}</td>
                                            <td>{{ $e->grand_total }}</td>
                                            <td>{{ Carbon::parse($e->created_at)->format('Y-m-d') }}</td>
                                            <td>{{ $e->status }}</td>


                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">
                                                <i class="fa fa-info text-warning mr-1"></i>
                                                No Orders
                                            </td>
                                        </tr>
                                        </tr>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 p-md-0 ">
                <div class="card m-2" style="height:500px; overflow:scroll">
                    <div class="card-header">
                        <h4>Latest Customers</h4>
                    </div>
                    <div class="card-body">

                        <table class="table header-border table-responsive-sm">
                            <thead>
                                <tr>
                                    <th class="font-weight-bold text-dark">#</th>
                                    <th class="font-weight-bold text-dark">Name</th>
                                    <th class="font-weight-bold text-dark">Email</th>
                                    <th class="font-weight-bold text-dark">Phone</th>
                                    <th class="font-weight-bold text-dark">No. of order</th>

                                </tr>
                            </thead>
                            <tbody>

                                @forelse ($latestUsers as $key=> $e)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $e->name }}</td>
                                        <td>{{ $e->email }}</td>
                                        <td>{{ $e->phone }}</td>
                                        <td>{{ $e->orders->count() }}</td>

                                        <td>{{ $e->status }}</td>


                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">
                                            <i class="fa fa-info text-warning mr-1"></i>
                                            No Customers
                                        </td>
                                    </tr>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>



                    </div>
                </div>
            </div>


        </div>



    </div>


    @section('scripts')
        
        <script>
            $.ajax({
                url: "{{ route('admin.orders.count') }}",
                method: 'GET',
                success: function(response) {
                    // Handle the successful response
                    var completedCount = response.completedCount;
                    var cancelledCount = response.cancelledCount;
                    var pendingCount = response.pendingCount;
                    var processingCount = response.processingCount;

                    createOrderStatusChart({
                        completedCount,
                        cancelledCount,
                        pendingCount,
                        processingCount
                    });
                },
                error: function(xhr, status, error) {
                    // Handle the error
                    console.log(error);
                }
            });

            function createOrderStatusChart({
                completedCount,
                cancelledCount,
                pendingCount,
                processingCount
            }) {
                // Retrieve the canvas element
                const canvas = document.getElementById('orderStatusChart');
                const ctx = canvas.getContext('2d');

                // Define the chart data
                const data = {
                    labels: ['Completed', 'Pending', 'Cancelled', 'Processing'],
                    datasets: [{
                        label: 'Order Status',
                        data: [completedCount, pendingCount, cancelledCount,
                            processingCount
                        ], // Replace with your actual order status counts
                        backgroundColor: [
                            '#0AAE59',
                            '#FFF275',
                            '#D7263D',
                            '#006992',
                        ],
                    }, ],
                };

                // Define the chart configuration
                const config = {
                    type: 'bar',
                    data: data,
                    options: {
                        scales: {
                            x: {
                                grid: {
                                    display: false,
                                },
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0,
                                },
                            },
                        },
                        maintainAspectRatio: false
                    },
                };

                // Create and render the chart
                new Chart(ctx, config);
            }

            // Toatal payment Type Chart

            $.ajax({
                url: "{{ route('admin.payments.count') }}",
                method: 'GET',
                success: function(response) {
                    // Handle the successful response
                    var data = response;

                    // Process the data and create the chart
                    createPaymentChart(data);
                },
                error: function(xhr, status, error) {
                    // Handle the error
                    console.log(error);
                }
            });


            function createPaymentChart(data) {
                // Prepare the data for the chart
                var labels = Object.keys(data);
                var counts = Object.values(data);

                // Create the chart using Chart.js
                var ctx = document.getElementById('paymentChart').getContext('2d');
                var chart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: counts,
                            backgroundColor: [
                                '#197BBD',
                                "#750D37", // Color for the first data item
                                '#FFF275',
                                "#F6511D" // Color for the second data item
                                // Add more colors for additional data items
                            ]
                        }]
                    },
                    options: {
                  
                        maintainAspectRatio: false
                    }
                });
            }
        </script>
    @endsection
@endsection
