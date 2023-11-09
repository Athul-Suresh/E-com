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
                    <h4> Brands </h4>
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

        {{-- @if(session('success'))
            <div class="alert alert-success">xcxv{{ session('success') }}</div>
        @endif --}}
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Manage Brands</h4>
                        <div class="d-sm-flex justify-content-between">
                            <a href="{{ route('admin.brands.index') }}" class="btn-sm btn-success text-white">All Brands</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="col-lg-3 mb-3">
                            <img src="{{asset('assets/images/brandLogo.png')}}" alt="Image preview">

                        </div>
                        <div class="col-lg-6">

                            <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="form-group">
                                    <label for="name">Brand Name </label>
                                    <input type="text" class="form-control" id="name" value="{{old('name')}}" name="name" placeholder="Enter a Brand">
                                    @error('name')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="category">Category {{ old('category')}}</label>
                                    <select class="form-control" id="category" name="category">
                                        <option value="">Choose Category</option>
                                        @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach

                                    </select>

                                    @error('category')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>



                                <div class="form-group">
                                    <label for="logo">Brand Logo</label>
                                    <input type="file" accept="image/jpeg, image/png, image/gif" class="form-control-file" id="file" name="logo">

                                    @error('logo')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>

                                <a href="{{route('admin.brands.index')}}" class="btn  btn-danger mt-4 pr-4 pl-4">Back</a>
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
  function previewFile() {
    const preview = document.querySelector('img');
    const file = document.querySelector('input[type=file]').files[0];
    const reader = new FileReader();

    reader.addEventListener("load", function () {
      preview.src = reader.result;
    }, false);

    if (file) {
      reader.readAsDataURL(file);
    }
  }

  const fileUpload = document.querySelector('input[type=file]');
  fileUpload.addEventListener('change', previewFile);

</script>

@endsection
