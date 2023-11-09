


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
                    <h4>All Product Tag</h4>
                    <p class="mb-0">Manage and organize product tags for efficient categorization and filtering of your products.</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Admin</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Product Tag</a></li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Update Unit</h4>
                        <div class="d-sm-flex justify-content-between">
                            <a href="{{ route('admin.productTags.index') }}" class="btn-sm btn-success text-white">All Product Tag</a>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="col-lg-6">
                            <form action="{{ route('admin.productTags.update',$productTag->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label for="name">Product Tag Name</label>
                                    <input type="text" class="form-control" id="name" value="{{$productTag->name}}" name="name" placeholder="Enter a Product Tag">

                                    @error('name')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>



                                <a href="{{route('admin.productTags.index')}}" class="btn  btn-danger mt-4 pr-4 pl-4">Back</a>
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
