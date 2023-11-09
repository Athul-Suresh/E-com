@extends('admin.dashboard.layouts.master')

@section('styles')
    <style>

    </style>
@endsection

@section('admin-content')


    <div class="container-fluid">
        <div class="row page-titles mx-0">

            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Manage Enquiry</h4>
                    <p class="mb-0">View and access all incoming enquiries from one centralized location.</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Admin</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Products</a></li>
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
                        <h4 class="card-title">Manage Enquiry</h4>
                        <div class="d-sm-flex justify-content-between">
                            <button id="downloadButton" class="btn btn-sm btn-info text-white">Download Excel</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table header-border table-responsive-sm product-datatable w-100" id="product-datatable">
                                    <thead>
                                        <tr>
                                            <th class="font-weight-bold text-dark">#</th>
                                            <th class="font-weight-bold text-dark">Name</th>
                                            <th class="font-weight-bold text-dark">Email</th>
                                            <th class="font-weight-bold text-dark">Phone</th>
                                            <th class="font-weight-bold text-dark">Subject</th>
                                            <th class="font-weight-bold text-dark">Message</th>
                                            <th class="font-weight-bold text-dark exclude">Action</th>

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
            url: "{{ route('admin.enquiry.index') }}",
            method: "GET",
            dataType: "json",
            success: function(response) {
                // Handle the successful response here
                datad = response.data;
                console.log(response.data);
            },
            error: function(xhr, status, error) {
                // Handle the error here
                console.log("Error:", error);
            }
        });

        const customFields = [{
                label: "#",
                value: "id"
            },

            {
                label: "Name",
                value: "name"
            },
            {
                label: "Email",
                value: "email"
            },
            {
                label: "Phone",
                value: "phone"
            },
            {
                label: "Subject",
                value: "subject"
            },
            {
                label: "Message",
                value: "message"
            },
            

        ];

        function generateExcel() {
            const wb = XLSX.utils.book_new();
            const ws = XLSX.utils.json_to_sheet(datad.map(item => {
                const row = {};
                customFields.forEach(field => {
                    if (typeof field.value === "function") {
                        row[field.label] = field.value(item);
                    } else {
                        row[field.label] = item[field.value];
                    }
                });
                return row;
            }));

            XLSX.utils.book_append_sheet(wb, ws, 'Sheet1');

            // Save the workbook as an Excel file
            XLSX.writeFile(wb, 'enquiry.xlsx');
        }



        // Add click event listener to the button
        const downloadButton = document.getElementById('downloadButton');
        downloadButton.addEventListener('click', generateExcel);


        $(function() {

            var table = $('.product-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.enquiry.index') }}",
                columnDefs: [
                    {
                        targets: 0,
                        render: function(data, type, row, meta) {
                           return meta.row;
                        }
                    },
                    {
                        targets: 1,
                        render: function(data, type, row, meta) {
                            return truncateText(data, 50)
                        }
                    },
                    {
                        targets: 4,
                        render: function(data, type, row, meta) {
                            return truncateText(data, 50)
                        }
                    },

                    {
                        targets: 6,
                        render: function(data, type, row, meta) {
                            var deleteUrl = "{{ route('admin.enquiry.destroy', ':id') }}"
                                .replace(':id', row.id);

                            return `
                        <a class="" href="${deleteUrl}" onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this Enquiry?')) { document.getElementById('delete-form-${row.id}').submit(); }">
                            <i class="fa fa-trash text-danger btn"></i>
                        </a>
                        <form id="delete-form-${row.id}" action="${deleteUrl}" method="POST" style="display: none;">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        </form>`;
                        }
                    }



                ],
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },

                    {
                        data: 'phone',
                        name: 'phone'
                    },

                    {
                        data: 'subject',
                        name: 'subject'
                    },

                    {
                        data: 'message',
                        name: 'message'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },



                ],
            });

        });
    </script>
@endsection

