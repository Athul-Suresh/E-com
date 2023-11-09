


@extends('admin.dashboard.layouts.master')

@section('styles')
   <style>
    .error{
        color: red;
    }
    img {
        max-width: 100%;
        max-height: auto;
        width: 120px;
        height: 120px;
        object-fit: cover;
    }
   </style>
@endsection

@section('admin-content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">

            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>All Units</h4>
                    <p class="mb-0">View and manage all units or products available in your inventory.</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Admin</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Units</a></li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Update Unit</h4>
                        <div class="d-sm-flex justify-content-between">
                            <a href="{{ route('admin.units.index') }}" class="btn-sm btn-success text-white">All Units</a>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="col-lg-6">
                            <form action="{{ route('admin.units.update',$unit->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label for="name">Unit Name</label>
                                    <input type="text" class="form-control" id="name" value="{{$unit->name}}" name="name" placeholder="Enter a Unit">

                                    @error('name')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>



                                <a href="{{route('admin.units.index')}}" class="btn  btn-danger mt-4 pr-4 pl-4">Back</a>
                                <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Save</button>

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
