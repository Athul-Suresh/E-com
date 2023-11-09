@extends('admin.dashboard.layouts.master')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datepicker/1.0.10/datepicker.min.css" integrity="sha512-YdYyWQf8AS4WSB0WWdc3FbQ3Ypdm0QCWD2k4hgfqbQbRCJBEgX0iAegkl2S1Evma5ImaVXLBeUkIlP6hQ1eYKQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('admin-content')
    <div class="container-fluid">


            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            Sales Report
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-5">
                                    <div id="dateFilter" class="d-flex justify-content-between">
                                        <div class="form-group">
                                            <label for="startDate">From Date:</label>
                                            <input type="text" id="startDate" name="startDate" class="form-control datepicker">
                                        </div>
                                        <div class="form-group">
                                            <label for="endDate">To Date:</label>
                                            <input type="text" id="endDate" name="endDate" class="form-control datepicker">
                                        </div>

                                      </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table header-border table-responsive-sm sales-datatable w-100"
                                    id="sales-datatable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Date/Time</th>
                                            <th>Order ID</th>
                                            <th>Customer Name</th>
                                            <th>Product Name</th>
                                            <th>Quantity</th>
                                            <th>Unit Price</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      {{-- @dd($data) --}}
                                            @forelse ($data as $index=> $item)
                                            <tr>
                                                <td>{{$index+1}}</td>
                                                <td>{{$item->created_at->format('d/m/Y, ')}}</td>
                                                <td>{{$item->order->order_number}}</td>
                                                <td>{{$item->order->user->name}}</td>
                                                <td>{{$item->product->name}}</td>
                                                <td>{{$item->product_qty}}</td>
                                                <td>{{$item->product->unit_price}}</td>
                                                <td>{{$item->product->unit_price*$item->product_qty}}</td>

                                            </tr>
                                            @empty

                                            @endforelse

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
<script src="https://cdnjs.cloudflare.com/ajax/libs/datepicker/1.0.10/datepicker.min.js"
 integrity="sha512-RCgrAvvoLpP7KVgTkTctrUdv7C6t7Un3p1iaoPr1++3pybCyCsCZZN7QEHMZTcJTmcJ7jzexTO+eFpHk4OCFAg=="
 crossorigin="anonymous"
 referrerpolicy="no-referrer">
</script>
<script>
    $(document).ready(function() {

        $('#startDate').datepicker({
            format: 'dd-mm-yyyy',
            endDate: '1d',



        });
        $('#endDate').datepicker({
            format: 'dd-mm-yyyy',
            endDate: '1d',



        });
    });

</script>
@endsection
