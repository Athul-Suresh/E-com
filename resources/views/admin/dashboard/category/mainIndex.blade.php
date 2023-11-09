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
                    <h4>All Categories</h4>
                    <p class="mb-0">View and manage all categories available on your platform or website.</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Admin</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Categories</a></li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Main Categories</h4>
                        <div class="d-sm-flex justify-content-between">
                            <a href="{{ route('admin.maincategories.create') }}" class="btn-sm btn-success text-white">Add Main Category</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table header-border table-responsive-sm">
                                    <thead>
                                        <tr>
                                            <th class="font-weight-bold text-dark">#</th>
                                            <th class="font-weight-bold text-dark">Category Name</th>
                                            <th class="font-weight-bold text-dark">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($mainCategory as $key=> $category)
                                            <tr>
                                                <td>{{$key+1}}</td>
                                                <td>{{$category->name}}</td>

                                                <td>
                                                    <a class="" href="{{ route('admin.maincategories.edit', $category->id) }}">
                                                        <i class="fa fa-edit text-warning btn"></i>
                                                    </a>
                                                    <a class=""
                                                        href="{{ route('admin.maincategories.destroy', $category->id) }}"
                                                        onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this Parent Category?')) { document.getElementById('delete-form-{{ $category->id }}').submit(); }">
                                                        <i class="fa fa-trash text-danger btn"></i>
                                                    </a>
                                                    <form id="delete-form-{{ $category->id }}"
                                                        action="{{ route('admin.maincategories.destroy', $category->id) }}"
                                                        method="POST" style="display: none;">
                                                        @method('DELETE')
                                                        @csrf
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center">
                                                <i class="fa fa-info text-warning mr-1"></i>
                                                No Category to list
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
