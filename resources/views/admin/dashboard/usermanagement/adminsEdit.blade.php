@extends('admin.dashboard.layouts.master')

@section('styles')
    <style>

    </style>
@endsection

@section('admin-content')
    @php
        use App\Models\User;
    @endphp

    <div class="container-fluid">
        <div class="row page-titles mx-0">

            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Admin Users</h4>
                    <p class="mb-0">Manage and control user access and permissions within the system.</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Admin</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Admin User</a></li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Admin</h4>
                    </div>
                    <div class="card-body">
                        <div class="card-body">
                            <div class="text-dark">


                                <form action="{{ route('admin.admins.update', $admin->id) }}" method="POST">
                                    @method('PUT')
                                    @csrf
                                    <div class="form-row">
                                        <div class="form-group col-md-6 col-sm-12">
                                            <label for="name">Admin Name</label>
                                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="{{ $admin->name }}">
                                        </div>
                                        <div class="form-group col-md-6 col-sm-12">
                                            <label for="email">Admin Email</label>
                                            <input type="text" class="form-control" id="email" name="email" placeholder="Enter Email" value="{{ $admin->email }}">
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6 col-sm-12">
                                            <label for="password">Password</label>
                                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password">
                                        </div>
                                        <div class="form-group col-md-6 col-sm-12">
                                            <label for="password_confirmation">Confirm Password</label>
                                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Enter Password">
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6 col-sm-6">
                                            <label for="password">Assign Roles</label>
                                            <select name="roles[]" id="roles" class="form-control select2" multiple>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->name }}" {{ $admin->hasRole($role->name) ? 'selected' : '' }}>{{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6 col-sm-6">
                                            <label for="username">Admin Username</label>
                                            <input type="text" class="form-control" id="username" name="username" placeholder="Enter Username" required value="{{ $admin->username }}">
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Save Admin</button>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>


        </div>
    </div>
@endsection


@section('scripts')
    <script></script>
@endsection
