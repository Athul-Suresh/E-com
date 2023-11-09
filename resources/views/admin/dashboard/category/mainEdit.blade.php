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

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Update Main Category</h4>
                        <div class="d-sm-flex justify-content-between">
                            <a href="{{ route('admin.maincategories.index') }}" class="btn-sm btn-success text-white">Main Category</a>
                        </div>
                    </div>
                    <div class="card-body">
                        {{-- @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif --}}

                        <div class="col-lg-8">

                            <form action="{{ route('admin.maincategories.update',$category->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="form-row mb-20">
                                    <div class="col-sm-4">
                                        <label class="font-14 bold black">Name </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="name" class="form-control"  value="{{$category->name}}"

                                            placeholder="Type here">
                                    </div>
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>






                                <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Update</button>
                            </form>
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
