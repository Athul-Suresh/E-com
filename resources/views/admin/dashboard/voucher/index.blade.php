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
                    <h4>All Vouchers</h4>
                    <p class="mb-0">Lorem ipsum dolor sit amet consectetur adipisicing elit. Nulla, temporibus.</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Admin</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Vouchers</a></li>
                </ol>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success text-white  alert-dismissible fade show" role="alert">{{ session('success') }}
            </div>
        @elseif (session('error'))
            <div class="alert alert-danger text-white  alert-dismissible fade show" role="alert">{{ session('error') }}
            </div>
        @endif

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Manage Vouchers</h4>
                        <div class="d-sm-flex justify-content-between">
                            <a href="{{ route('admin.vouchers.create') }}" class="btn-sm btn-success text-white">Add Voucher</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table header-border table-responsive-sm">
                                    <thead>
                                        <tr>
                                            <th class="font-weight-bold text-dark">#</th>
                                            <th class="font-weight-bold text-dark">Name</th>
                                            <th class="font-weight-bold text-dark">Discount</th>

                                            <th class="font-weight-bold text-dark">Expires At</th>
                                            <th class="font-weight-bold text-dark">Usage Limit</th>
                                            <th class="font-weight-bold text-dark">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($vouchers as $key => $voucher)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>

                                                <td>{{ $voucher->voucher_code }}</td>
                                                <td>{{ $voucher->discount }}</td>
                                                <td>{{ date('d-m-Y',strtotime($voucher->expires_at)) }}</td>
                                                <td>{{ $voucher->usage_limit }}</td>

                                                <td>
                                                    <div class="custom-control custom-switch custom-switch-md">
                                                        <input type="checkbox" class="custom-control-input"
                                                            onclick="setStatus({{ $voucher->id }})"
                                                            id="statusSwitch-{{ $voucher->id }}"
                                                            {{ $voucher->status == 1 ? 'checked' : '' }}>
                                                        <label class="custom-control-label"
                                                            for="statusSwitch-{{ $voucher->id }}">
                                                            <span id="statusSwitchTxt{{ $voucher->id }}">
                                                                {{ $voucher->status == 1 ? 'Active' : 'Inactive' }}

                                                            </span>
                                                        </label>
                                                    </div>


                                                    {{-- <span id="status" role="button" onclick="setStatus({{ $voucher->id }})"
                                                        class="badge  badge-pill text-white  badge-{{ $voucher->status == 1 ? 'success' : 'warning' }}">{{ $voucher->status == 1 ? 'Active' : 'Inactive' }}</span>
                                                --}}
                                                </td>

                                                <td>
                                                    <a class="" href="{{ route('admin.brands.edit', $voucher->id) }}">
                                                        <i class="fa fa-edit text-warning btn"></i>
                                                    </a>
                                                    <a class=""
                                                        href="{{ route('admin.brands.destroy', $voucher->id) }}"
                                                        onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this brand?')) { document.getElementById('delete-form-{{ $voucher->id }}').submit(); }">
                                                        <i class="fa fa-trash text-danger btn"></i>
                                                    </a>
                                                    <form id="delete-form-{{ $voucher->id }}"
                                                        action="{{ route('admin.brands.destroy', $voucher->id) }}"
                                                        method="POST" style="display: none;">
                                                        @method('DELETE')
                                                        @csrf
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach


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
        function setStatus(id) {
            var route = "{{ route('admin.vouchers.updateStatus', ':id') }}";
            route = route.replace(':id', id);
            $.ajax({
                url: route,
                type: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    if (response.success == true) {
                        if (response.status == 1) {
                            $('#statusSwitchTxt' + id).text('Active');
                            $('#statusSwitch-' + id).prop('checked');
                        } else {
                            $('#statusSwitchTxt' + id).text('Inactive');
                        }
                    }

                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }
    </script>
@endsection
