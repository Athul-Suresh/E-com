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
                    <h4>All Products</h4>
                    <p class="mb-0">View and manage all products in your inventory from a centralized dashboard.</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Products</a></li>
                </ol>
            </div>


        </div>

        @if (Session::has('success'))
            <div class="alert alert-success text-white  alert-dismissible fade show" role="alert">
                {{ Session::get('success') }}
            </div>
        @endif

        @if (Session::has('error'))
            <div class="alert alert-danger text-white  alert-dismissible fade show" role="alert">
                {{ Session::get('error') }}
            </div>
        @endif



        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Manage Products</h4>
                        <div class="d-grid gap-4 justify-content-between">

                            <button id="downloadButton" class="btn btn-sm btn-info text-white">Download Excel</button>
                            <a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-success text-white">
                                Add Product
                            </a>

                        </div>
                    </div>
                    <div class="card-body">
                      
                            <div class="table-responsive">
                                <table class="table header-border table-responsive-sm product-datatable w-100"
                                    id="product-datatable">
                                    <thead>
                                        <tr>
                                            <th class="font-weight-bold text-dark">#</th>
                                            <th class="font-weight-bold text-dark exclude">Product Image</th>
                                            <th class="font-weight-bold text-dark">Product Name</th>
                                            <th class="font-weight-bold text-dark">Category</th>
                                            <th class="font-weight-bold text-dark">Info</th>
                                            <th class="font-weight-bold text-dark">Stock & Sales</th>
                                            <th class="font-weight-bold text-dark">Featured</th>
                                            <th class="font-weight-bold text-dark">Published</th>
                                            <th class="font-weight-bold text-dark exclude">Actions</th>
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
        function setFeatured(id) {
            var route = "{{ route('admin.products.updateFeature', ':id') }}";
            route = route.replace(':id', id);
            $.ajax({
                url: route,
                type: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    if (response.success == true) {

                        toastr.success('Feature updated successfully')
                        $('#statusSwitch-' + id).prop('checked');

                    }

                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }

        function setPublished(id) {
            var route = "{{ route('admin.products.updateStatus', ':id') }}";
            route = route.replace(':id', id);
            $.ajax({
                url: route,
                type: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    if (response.success == true) {
                        toastr.success('Status updated successfully')
                        $('#publishSwitch-' + id).prop('checked');

                    }

                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }

        function truncateText(text, maxLength) {
            if (text.length > maxLength) {
                return text.substring(0, maxLength) + "...";
            }
            return text;
        }

        var datad = [];

        $.ajax({
            url: "{{ route('admin.products.index') }}",
            method: "GET",
            dataType: "json",
            success: function(response) {
                // Handle the successful response here
                datad = response.data;
            },
            error: function(xhr, status, error) {
                // Handle the error here
                console.log("Error:", error);
            }
        });

        function extractValue(info, prefix) {
            const startIndex = info.indexOf(prefix);
            if (startIndex !== -1) {
                const valueStartIndex = startIndex + prefix.length;
                const valueEndIndex = info.indexOf('\n', valueStartIndex);
                if (valueEndIndex !== -1) {
                    const value = info.substring(valueStartIndex, valueEndIndex).trim();
                    return value;
                }
            }
            return '';
        }

        // Helper function to extract values from the nested div elements in the info cell
        function extractValuesFromInfo(cell) {
            const divs = Array.from(cell.querySelectorAll('div'));
            const values = {};

            // Recursive function to extract values from nested divs
            function extractNestedDivValues(nestedDivs) {
                nestedDivs.forEach((div) => {
                    const text = div.innerText.trim();

                    // Extract key-value pairs based on the colon separator
                    const separatorIndex = text.indexOf(':');
                    if (separatorIndex !== -1) {
                        const key = text.substring(0, separatorIndex).trim();
                        const value = text.substring(separatorIndex + 1).trim();
                        values[key] = value;
                    }

                    // Check for nested divs
                    const nestedDivElements = div.querySelectorAll('div');
                    if (nestedDivElements.length > 0) {
                        extractNestedDivValues(Array.from(nestedDivElements));
                    }
                });
            }

            // Start extracting values from top-level divs
            extractNestedDivValues(divs);
            return values;
        }



        function generateExcel() {
            // Get the table headers
            const headers = ["#", "Product Name", "Category", "Info", "Stock & Sales", "Featured", "Published"];

            // Create the data array with headers
            const data = [headers];

            // Get the table rows
            const table = document.getElementById('product-datatable');
            const rows = Array.from(table.querySelectorAll('tr:not(.exclude)'));

            // Get the indexes of excluded columns
            const excludedColumns = Array.from(table.querySelectorAll('th.exclude')).map(th => Array.from(th.parentNode
                .children).indexOf(th));

            // Loop through each row
            rows.forEach((row) => {
                const rowData = [];

                // Loop through each cell in the row
                const cells = row.querySelectorAll('td');
                cells.forEach((cell, index) => {
                    // Check if the current cell belongs to an excluded column
                    if (!excludedColumns.includes(index)) {
                        const text = cell.innerText.trim();

                        // If the cell contains a checkbox, check its value
                        const checkbox = cell.querySelector('input[type="checkbox"]');
                        if (checkbox) {
                            const isChecked = checkbox.checked;
                            const displayValue = isChecked ? 'Yes' : 'No';
                            rowData.push(displayValue);
                        } else if (index === 3) { // Check if it's the "Category" column
                            const liValues = Array.from(cell.querySelectorAll('li')).map(li => li.innerText
                                .trim());
                            const categoryValue = liValues.join(', '); // Join the li values with comma
                            rowData.push(categoryValue);
                        } else if (index === 4) { // Check if it's the "Info" column
                            // Extract the specific values from the nested div elements
                            const values = extractValuesFromInfo(cell);

                            // Render the extracted values
                            const purchasePrice = values['Purchase Price'] ||
                                ''; // If "Purchase Price" value is not found, set it to an empty string
                            const unitPrice = values['Unit Price'] ||
                                ''; // If "Unit Price" value is not found, set it to an empty string
                            const discount = values['Discount'] ||
                                ''; // If "Discount" value is not found, set it to an empty string
                            const info =
                                `Purchase Price: ${purchasePrice}, Unit Price: ${unitPrice}, Discount: ${discount}`;
                            rowData.push(info);
                        } else if (index === 5) { // Check if it's the "Info" column
                            // Extract the specific values from the nested div elements
                            const values = extractValuesFromInfo(cell);

                            // Render the extracted values
                            const stock = values['Stock'] ||
                                ''; // If "Stock" value is not found, set it to an empty string
                            const sales = values['Sales'] ||
                                ''; // If "Sales" value is not found, set it to an empty string

                            // Combine the stock and sales values into a single string
                            const stockAndSales = `Stock: ${stock}, Sales: ${sales}`;

                            rowData.push(stockAndSales);


                        } else {
                            rowData.push(text);
                        }
                    }
                });

                // Add the row data to the data array
                data.push(rowData);
            });

            // Create a workbook
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
            saveAs(excelBlob, 'products.xlsx');
        }




        // Add click event listener to the button
        const downloadButton = document.getElementById('downloadButton');
        downloadButton.addEventListener('click', generateExcel);


        $(function() {

            var table = $('.product-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.products.index') }}",
                columnDefs: [{
                        targets: 3,
                        render: function(data, type, row, meta) {
                            var html = "";

                            if (Array.isArray(JSON.parse(data))) {
                                JSON.parse(data).forEach(function(obj) {
                                    if (obj.name) {
                                        html +=
                                            "<li><i class='fa fa-dot-circle-o'></i>" +
                                            obj.name + "</li>";
                                    }
                                });
                            }

                            return html;
                        }
                    },
                    {
                        targets: 2,
                        render: function(data, type, row, meta) {
                            return truncateText(data, 50)
                        }
                    },
                    {
                        targets: 1,

                         render: function(data, type, row, meta) {
                            var imageUrl = (typeof data !== 'undefined') ? "{{ asset('storage/uploads/product/thumbnail/') }}/" + data : '';
                            return `<img class="profile" src="${imageUrl}" alt="Image">`;
                        }
                    },

                    {
                        targets: 4,
                        render: function(data, type, row, meta) {
                            return `

                                                    <div class="d-flex purchase-price">
                                                        <strong>Purchase Price: </strong>
                                                        <div class="ml-1">
                                                            <div class="d-flex">
                                                                <div>
                                                                    ${row.purchase_price}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex unit-price"><strong>Unit Price: </strong>
                                                        <div class="ml-1">
                                                            <div class="d-flex">
                                                                <div>
                                                                    ${row.unit_price}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex product discount">
                                                        <strong>Discount: </strong>
                                                        <div class="ml-1">
                                                            <div class="d-flex">

                                                                    <div>   ${row.discount_type == 1 ? "Yes" : "No"} </div>

                                                            </div>
                                                        </div>
                                                    </div>


                `;
                        }
                    },

                    {
                        targets: 5,
                        render: function(data, type, row, meta) {
                            return `

                                                    <div class="d-flex purchase-price">
                                                        <strong>Stock: </strong>
                                                        <div class="ml-1">
                                                            <div class="d-flex">
                                                                <div>
                                                                    ${row.stock}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex unit-price"><strong>Sales: </strong>
                                                        <div class="ml-1">
                                                            <div class="d-flex">
                                                                <div>
                                                                   D
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    </div>

                `;
                        }
                    },


                    {
                        targets: 6,
                        render: function(data, type, row, meta) {
                            return row.featured == 1 ?
                                `<div class="custom-control custom-switch custom-switch-md">
           <input type="checkbox" class="custom-control-input" onclick="setFeatured(${row.id})" id="statusSwitch-${row.id}" checked>
           <label class="custom-control-label" for="statusSwitch-${row.id}"></label>
         </div>` :
                                `<div class="custom-control custom-switch custom-switch-md">
           <input type="checkbox" class="custom-control-input" onclick="setFeatured(${row.id})" id="statusSwitch-${row.id}">
           <label class="custom-control-label" for="statusSwitch-${row.id}"></label>
         </div>`;
                        }
                    },

                    {
                        targets: 7,
                        render: function(data, type, row, meta) {
                            return row.status == 1 ?
                                `<div class="custom-control custom-switch custom-switch-md">
           <input type="checkbox" class="custom-control-input" onclick="setPublished(${row.id})" id="publishSwitch-${row.id}" checked>
           <label class="custom-control-label" for="publishSwitch-${row.id}"></label>
         </div>` :
                                `<div class="custom-control custom-switch custom-switch-md">
           <input type="checkbox" class="custom-control-input" onclick="setPublished(${row.id})" id="publishSwitch-${row.id}">
           <label class="custom-control-label" for="publishSwitch-${row.id}"></label>
         </div>`;
                        }
                    },

                    {
                        targets: 8,
                        render: function(data, type, row, meta) {
                            var editUrl = "{{ route('admin.products.edit', ':id') }}".replace(':id', row.id);
                            var deleteUrl = "{{ route('admin.products.destroy', ':id') }}".replace(':id', row.id);

                            return `
            <a class="" href="${editUrl}">
                <i class="fa fa-edit text-warning btn"></i>
            </a>
            <a class="" href="${editUrl}" onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this Product?')) { document.getElementById('delete-form-${row.id}').submit(); }">
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
                        data: 'thumbnail',
                        name: 'thumnail'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'category',
                        name: 'category'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'stock',
                        name: 'stock'
                    },
                    {
                        data: 'featured',
                        name: 'featured'
                    },
                    {
                        data: 'status',
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
