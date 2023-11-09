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
                    <p class="mb-0">View and manage all orders received through your E-commerce website.</p>
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
                        <div class="d-grid gap-4 justify-content-between">

                            <button id="downloadButton" class="btn btn-sm btn-info text-white">Download Excel</button>



                        </div>
                        {{-- <div class="d-sm-flex justify-content-between">
                            <a href="{{ route('admin.orders.create') }}" class="btn-sm btn-success text-white">Add
                                Product</a>
                        </div> --}}
                    </div>
                    <div class="card-body">

                            <div class="table-responsive">
                                <table class="table header-border table-responsive-sm product-datatable" id="product-datatable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Order Number</th>
                                                <th>Customer</th>
                                                <th>Items</th>
                                                <th>Grand Total</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th class="exclude">Action</th>
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

        var datad = [];


        $.ajax({
            url: "{{ route('admin.orders.index') }}",
            method: "GET",
            dataType: "json",
            success: function(response) {
                // Handle the successful response here
                datad = response.data;
                // console.log(response.data);
            },
            error: function(xhr, status, error) {
                // Handle the error here
                // console.log("Error:", error);
            }
        });

        const customFields = [{
                label: "#",
                value: "id"
            },
            {
                        label: 'Order Number',
                        value: 'order_number'
                    },
                    {
                        label: 'Customer',
                        value: 'customer'
                    },
                    {
                        label: 'Items',
                        value: 'items'
                    },
                    {
                        label: 'Grand Total',
                        value: 'grand_total'
                    },
                    {
                        label: 'Date',
                        value: 'date'
                    },
                    {
                        label: 'Status',
                        value: 'status'
                    },



        ];

        function generateExcel() {
            const headers = ["#", "Order Number", "Customer", "Items", "Grand Total", "Date", "Status"];
            const data = [headers];
            const table = document.getElementById('product-datatable');
            const rows = Array.from(table.querySelectorAll('tr:not(.exclude)'));
            const excludedColumns = Array.from(table.querySelectorAll('th.exclude')).map(th => Array.from(th.parentNode
                .children).indexOf(th));

            rows.forEach((row) => {
                const rowData = [];

                const cells = row.querySelectorAll('td');
                cells.forEach((cell, index) => {

                    if (!excludedColumns.includes(index)) {
                        const text = cell.innerText.trim();
                        if (index === 2) { // Check if it's the "Customer" column
                        rowData.push(text);
                        }

                        if (index === 3) { // Check if it's the "Items" column
                        rowData.push(text);
                        }
                    }
                    });
                    data.push(rowData);
                    });
                    const workbook = XLSX.utils.book_new();

                    // Create a worksheet
                    const worksheet = XLSX.utils.aoa_to_sheet(data);

                    // Add the worksheet to the workbook
                    XLSX.utils.book_append_sheet(workbook, worksheet, 'Sheet1');

                    // Generate the Excel file
                    const excelBuffer = XLSX.write(workbook, {
                        bookType: 'xlsx',
                        type: 'array'
                    });
                    const excelBlob = new Blob([excelBuffer], {
                        type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                    });

                    // Download the Excel file
                    saveAs(excelBlob, 'Orders.xlsx');

        }



        // Add click event listener to the button
        const downloadButton = document.getElementById('downloadButton');
        downloadButton.addEventListener('click', generateExcel);


        $(function() {

            var table = $('.product-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.orders.index') }}",
                columnDefs: [

                    {
                        targets: 0,
                        render: function(data, type, row, meta) {
                          return meta.row+1;
                        }
                    },
                    {
                        targets: 1,
                        render: function(data, type, row, meta) {

                           return row.order_number;
                        }
                    },
                    {
                        targets: 2,
                        render: function(data, type, row, meta) {

                            return row.user.name
                        }
                    },
                    {
                        targets: 3,
                        render: function(data, type, row, meta) {
                            return row.details.length
                        }
                    },
                    {
                        targets: 4,
                        render: function(data, type, row, meta) {
                           return row.grand_total;
                        }
                    },
                    {
                        targets: 5,
                        render: function(data, type, row, meta) {
                            return new Date(row.created_at).toLocaleDateString('en-GB');
                        }
                    },
                    {
                        targets: 6,
                        render: function(data, type, row, meta) {
                           return row.status;
                        }
                    },
                    {
                        targets: 7,
                        render: function(data, type, row, meta) {
                            var editUrl = "{{ route('admin.orders.edit', ':id') }}".replace(':id', row.id);
                           return `

                           <a class="text-dark"
                            href="${editUrl}">
                            <i class="fa fa-edit fa-1x text-warning "></i>
                            </a>

                           `;
                        }
                    },



                ],
                columns: [
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'Order Number',
                        name: 'order_number'
                    },
                    {
                        data: 'Customer',
                        name: 'customer'
                    },
                    {
                        data: 'Items',
                        name: 'items'
                    },
                    {
                        data: 'Grand Total',
                        name: 'grand_total'
                    },
                    {
                        data: 'Date',
                        name: 'date'
                    },
                    {
                        data: 'Status',
                        name: 'status'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false
                    },
                ],
            });

        });
    </script>
@endsection
