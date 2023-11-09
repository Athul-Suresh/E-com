@extends('admin.dashboard.layouts.master')

@section('styles')
@endsection

@section('admin-content')
    <div class="container-fluid">
        <div>
            <div>
                <div class="card">
                    <div class="card-header">
                        <h4>Stock Report</h4>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table header-border table-responsive-sm stock-datatable w-100 stock-datatable"
                                id="stock-datatable">
                                <thead>
                                    <tr>

                                        <th>#</th>
                                        <th>Product Name</th>
                                        <th>Num of Sale</th>
                                        <th>In Stock</th>

                                    </tr>
                                </thead>
                                <tbody>



                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="row mx-0 g-3 mt-3">
            <div class="col-lg-6 p-md-0">
                <div class="card m-2" style="min-height: 500px;">
                    <div class="card-header">
                        <h4>Stock Statistics</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="doughnutChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 p-md-0">
                <div class="card m-2" style="min-height: 500px;">
                    <div class="card-header">
                        <h4>Sales Statistics</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="lineChart"></canvas>
                    </div>
                </div>
            </div>
        </div>






    </div>
@endsection


@section('scripts')
    <script>

        
        function truncateText(text, maxLength) {
            if (text.length > maxLength) {
                return text.substring(0, maxLength) + "...";
            }
            return text;
        }

        $.ajax({
            url: "{{ route('stock-and-sales') }}",
            method: "GET",
            success: function(response) {
                var totalStock = response.totalStock;
                var totalSales = response.totalSales;

                // Create a doughnut chart
                var ctx = document.getElementById('doughnutChart').getContext('2d');
                var doughnutChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Total Stock', 'Total Sales'],
                        datasets: [{
                            data: [totalStock, totalSales],
                            backgroundColor: ['#FF6384', '#36A2EB']
                        }]
                    },
                    options: {
                        maintainAspectRatio: false
                    }
                });

                // Get the canvas element
                const lineChartCanvas = document.getElementById('lineChart');

                // Create the bar chart
                const lineChart = new Chart(lineChartCanvas, {
                    type: 'bar', // Change the chart type to 'bar'
                    data: {
                        labels: ['Total Stock', 'Total Sales'],
                        datasets: [{
                            label: 'Stock and Sales',
                            data: [totalStock, totalSales],
                            backgroundColor: '#0AAE59',

                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true // Start the y-axis at zero
                            }
                        }
                    }
                });



            },
            error: function(xhr, status, error) {
                console.log(error); // Handle the error if needed
            }
        });








        $(function() {
            var table = $('.stock-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.report.stock') }}",
                columnDefs: [{
                    targets: 1,
                    render: function(data, type, row, meta) {
                        return truncateText(data, 80);
                    }
                }],
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'total_sales',
                        name: 'total_sales'
                    },
                    {
                        data: 'stock',
                        name: 'stock'
                    }
                ]
            });
        });
    </script>
@endsection
