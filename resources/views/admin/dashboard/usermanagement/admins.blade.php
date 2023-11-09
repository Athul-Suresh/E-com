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
                        <h4 class="card-title">Manage Admins</h4>
                        <div class="d-sm-flex justify-content-between">
                            <a href="{{ route('admin.admins.create') }}" class="btn-sm btn-success text-white">Add Admin</a>
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
                                            <th class="font-weight-bold text-dark">Username</th>
                                            <th class="font-weight-bold text-dark">Email</th>
                                            <th class="font-weight-bold text-dark">Roles</th>
                                            <th class="font-weight-bold text-dark">Action</th>

                                        </tr>
                                    </thead>
                                    <tbody>

                                        <tbody>
                                            @forelse ($admins as $admin)
                                            <tr>
                                                <td>{{$admin->id}}</td>
                                                <td>{{$admin->name}}</td>
                                                <td>{{$admin->username}}</td>
                                                <td>{{$admin->email}}</td>
                                                <td>
                                                    <ul>
                                                        @foreach ($admin->roles as $role)
                                                        <li>
                                                            {{ $role->name }}
                                                        </li>
                                                        @endforeach
                                                    </ul>
                                                </td>

                                                <td>
                                                    @if (Auth::guard('admin')->user()->can('admin.edit'))
                                                        <a class="" href="{{ route('admin.admins.edit', $admin->id) }}">
                                                            <i class="fa fa-edit text-warning btn"></i>
                                                        </a>
                                                    @endif

                                                    @if (Auth::guard('admin')->user()->can('admin.delete'))
                                                    <a class="" href="{{ route('admin.admins.destroy', $admin->id) }}"
                                                    onclick="event.preventDefault(); document.getElementById('delete-form-{{ $admin->id }}').submit();">
                                                    <i class="fa fa-trash text-danger btn"></i>
                                                    </a>
                                                    <form id="delete-form-{{ $admin->id }}" action="{{ route('admin.admins.destroy', $admin->id) }}" method="POST" style="display: none;">
                                                        @method('DELETE')
                                                        @csrf
                                                    </form>
                                                    @endif
                                                </td>


                                            </tr>
                                            @empty

                                            @endforelse
                                        </tbody>

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

