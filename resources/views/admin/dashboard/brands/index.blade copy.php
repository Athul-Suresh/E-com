@extends('admin.dashboard.layouts.master')

@section('styles')
   <style>
     .profile{
        width:90px!important;
    }
    .error{
        color: red;
    }
   </style>
@endsection

@section('admin-content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">

            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>All Brands</h4>
                    <p class="mb-0">Lorem ipsum dolor sit amet consectetur adipisicing elit. Nulla, temporibus.</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Admin</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Brands</a></li>
                </ol>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success text-white  alert-dismissible fade show" role="alert">{{ session('success') }}</div>
        @elseif (session('error'))
            <div class="alert alert-danger text-white  alert-dismissible fade show" role="alert">{{ session('error') }}</div>
        @endif

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Manage Brands</h4>
                        <div class="d-sm-flex justify-content-between">
                            <a href="{{ route('admin.brands.create') }}" class="btn-sm btn-success text-white">Add Brand</a>
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
                                            <th class="font-weight-bold text-dark">Logo</th>
                                            <th class="font-weight-bold text-dark">Status</th>
                                            <th class="font-weight-bold text-dark">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($brands as $key=> $brand)
                                            <tr>
                                                <td>{{$key+1}}</td>

                                                <td>{{$brand->name}}</td>
                                                <td>
                                                    <img class="" width="50px"
                                                    src="{{ asset('uploads/admin/brands/'.$brand->logo) }}"
                                                    alt="{{$brand->name}}"
                                                    srcset="">
                                                </td>
                                                <td>
                                                    @if ($brand->status)
                                                    <span
                                                    class="badge bg-success text-white">Active</span>
                                                    @else
                                                    <span
                                                    class="badge bg-danger text-white">Inactive</span>
                                                    @endif


                                                </td>
                                                <td>

                                                    <a class="" href="{{ route('admin.brands.edit', $brand->id) }}">
                                                        <i class="fa fa-edit text-warning btn"></i>
                                                    </a>

                                                <a class="" href="{{ route('admin.brands.destroy', $brand->id) }}"
                                                onclick="event.preventDefault(); document.getElementById('delete-form-{{ $brand->id }}').submit();">
                                                <i class="fa fa-trash text-danger btn"></i>
                                                </a>
                                                <form id="delete-form-{{ $brand->id }}" action="{{ route('admin.brands.destroy', $brand->id) }}"
                                                     method="POST" style="display: none;">
                                                    @method('DELETE')
                                                    @csrf
                                                </form>


                                                </td>
                                            </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center">
                                                <i class="fa fa-info text-warning mr-1"></i>
                                                No Brands to List
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
        </div>
    </div>

    @endsection


@section('scripts')
<script>


</script>

@endsection
