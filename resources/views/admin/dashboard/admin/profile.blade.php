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
                    <h4>Hi, {{$data->name}} welcome back!</h4>
                    <p class="mb-0">Update your profile</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Admin</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Profile</a></li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Manage Profile</h4>
                    </div>
                    <div class="card-body">
                        <div class="card-body">
                            <div class="basic-form">

                                <form method="POST" action="{{ route('admin.profile.save') }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <img id="preview" class="profile" src="{{ $data->image ? asset('storage/uploads/admin/profile/' . $data->image) : asset('assets/images/avatar/1.png') }}" alt="Preview">
                                        </div>
                                    </div>


                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Full Name</label>
                                            <input type="text" name="name" class="form-control"
                                                value="{{ $data->name }}">
                                                @error('name')
                                                <span role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Email</label>
                                            <input type="email" name="email" class="form-control"
                                                value="{{ $data->email }}">
                                                @error('email')
                                                <span role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Phone</label>
                                            <input type="tel" name="phone" class="form-control"
                                                value="{{ $data->phone }}">
                                                @error('phone')
                                                <span role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>username</label>
                                            <input type="text" name="username" class="form-control"
                                                value="{{ $data->username }}">
                                                @error('username')
                                                <span role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Password</label>
                                            <input type="password" name="password" class="form-control"
                                                placeholder="Password">
                                                @error('password')
                                                <span role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Image</label>
                                            <input type="file" name="image" class="form-control-file" id="fileInput" onchange="previewFile();">
                                            @error('image')
                                            <span class="error">{{ $message }}</span>
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
            @php $roles = $data->getRoleNames(); @endphp


            <div class="col-lg-3 offset-3">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Activity</h4>
                    </div>
                    <div class="card-body">




                            @forelse ($log as $activity)
                            <div class="mb-2"><strong>!</strong>
                               <span class="h6"> {{$activity->subject}}</span>
                            </div>
                            @empty

                            @endforelse



                    </div>
                </div>
            </div>




        </div>
    </div>
@endsection


@section('scripts')
<script>

    function previewFile() {
        var preview = document.querySelector('#preview');
        var file    = document.querySelector('input[type=file]').files[0];
        var reader  = new FileReader();

        reader.onloadend = function () {
            preview.src = reader.result;
        }

        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.src = "";
        }

    }
</script>

@endsection
