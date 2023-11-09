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
                    <h4>Admin Roles</h4>
                    <p class="mb-0">Manage and assign different roles to administrators within the dashboard.</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Admin</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Roles</a></li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Manage Roles</h4>
                        <div class="d-sm-flex justify-content-between">
                            <a href="{{ route('admin.roles.create') }}" class="btn-sm btn-success text-white">Add Role</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table header-border table-responsive-sm">
                                    <thead>
                                        <tr>
                                            <th class="font-weight-bold text-dark">#</th>
                                            <th class="font-weight-bold text-dark">Role Name</th>
                                            <th class="font-weight-bold text-dark">Guard Name</th>
                                            <th class="font-weight-bold text-dark">Action</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($roles as $key=> $role)
                                        <tr>
                                            <th scope="row">{{$key+1}}</th>
                                            <td>{{$role->name}}</td>
                                            <td>{{$role->guard_name}}</td>

                                            <td class="">
                                                {{-- @if (Auth::guard('admin')->user()->can('role.view'))
                                                <a class="" href="{{ route('admin.roles.show', $role->id) }}">
                                                    <i class="fa fa-eye fa-2x btn text-info"></i>
                                                </a>
                                                @endif --}}

                                                @if (Auth::guard('admin')->user()->can('role.edit'))
                                                <a class="" href="{{ route('admin.roles.edit', $role->id) }}">
                                                    <i class="fa fa-pencil text-warning btn"></i>
                                                </a>
                                                @endif



                                                @if (Auth::guard('admin')->user()->can('role.delete'))
                                                <a class="" href="{{ route('admin.roles.destroy', $role->id) }}"
                                                    onclick="event.preventDefault(); document.getElementById('delete-form-{{ $role->id }}').submit();"
                                                    >  <i class="fa fa-trash btn text-danger"></i></a>

                                                    <form id="delete-form-{{ $role->id }}" action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" style="display: none;">
                                                        @method('DELETE')
                                                        @csrf
                                                    </form>

                                                @endif
                                            </td>


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
    </div>
@endsection


@section('scripts')
<script>


</script>

@endsection
