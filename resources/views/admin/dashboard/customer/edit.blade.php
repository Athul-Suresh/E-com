@extends('admin.dashboard.layouts.master')

@section('styles')

    <style>
        .error {
            color: red;
        }
    </style>
@endsection

@section('admin-content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">

            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4> Customers </h4>
                    <p class="mb-0">Manage and maintain a comprehensive list of your customers and their details.</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Admin</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Customers</a></li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Customer </h4>
                        <div class="d-sm-flex justify-content-between">
                            <a href="{{ route('admin.customers.index') }}" class="btn-sm btn-success text-white">All Customers</a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                        <div class="col-lg-8">
                            <form method="POST" action="{{ route('admin.customers.update',$customer->id) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="form-row">

                                    <div class="form-group col-md-6">
                                        <label>Full Name</label>
                                        <input type="text" name="name" class="form-control"
                                            value="{{ $customer->name }}">
                                            @error('name')
                                            <span role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Email</label>
                                        <input type="email" name="email" class="form-control"
                                            value="{{ $customer->email }}">
                                            @error('email')
                                            <span role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Phone</label>
                                        <input type="tel" name="phone" class="form-control"
                                            value="{{ $customer->phone }}">
                                            @error('phone')
                                            <span role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>




                                </div>

                                <button type="submit" class="btn btn-primary">Update</button>
                            </form>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')

@endsection
