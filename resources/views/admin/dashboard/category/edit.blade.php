@extends('admin.dashboard.layouts.master')

@section('styles')
   <style>
    .error{
        color: red;
    }
    .preview_image,
        .preview-image-wrapper img
        {
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
                    <h4>Categories</h4>
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
                        <h4 class="card-title">Update Categories</h4>
                        <div class="d-sm-flex justify-content-between">
                            <a href="{{ route('admin.categories.index') }}" class="btn-sm btn-success text-white">All Category</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="col-lg-3 mb-3">
                            <img src="{{asset('assets/images/brandLogo.png')}}" alt="Image preview">

                        </div>
                        <div class="col-lg-6">
                            <form action="{{ route('admin.categories.update',$category->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                    @method('PUT')
                                <div class="form-group">
                                    <label for="name">Category Name</label>
                                    <input type="text" class="form-control" id="name" value="{{$category->name}}" name="name" placeholder="Enter a Category">

                                    @error('name')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>

                                <div class="form-group">

                                        <label class="font-14 bold black ">Categories </label>


                                        <select class="form-control" name="parent">
                                            <option value="">Choose</option>
                                            @foreach ($categories as $cat)
                                                <option value="{{ $cat->id }}" {{$category->id==$cat->id?'selected':''}}>{{ $cat->name }}</option>
                                            @endforeach
                                        </select>

                                </div>


                                <div class="form-group">
                                    <label for="thumbnail">Thumbnail</label>
                                    {{$category->logo}}
                                    <input type="file" name="logo" accept="image/jpeg, image/png, image/gif" class="form-control-file" id="file" name="thumbnail">

                                    @error('logo')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>

                                <div class="form-row mb-20">
                                    <div class="col-sm-4">
                                        <label class="font-14 bold black">Featured Category </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" value="1" {{$category->featured==1?'checked':''}} name="featured" class="form-control-check">
                                    </div>

                                    @error('featured')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>




                                <h4 class="card-title mt-3 mb-3">SEO</h4>

                                <div class="form-row mb-20">
                                    <div class="col-sm-4">
                                        <label class="font-14 bold black">Meta Title </label>
                                    </div>
                                    <div class="col-sm-8">


                                        <input type="text" name="meta_title" class="form-control" value="{{$category->meta_title}}" maxlength="255"
                                            placeholder="">




                                        @error('meta_title')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-row mb-20">
                                    <div class="col-sm-4">
                                        <label class="font-14 bold black">Meta Keyword </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="meta_keyword" class="form-control " value="{{$category->meta_keyword}}" maxlength="255">
                                        @error('meta_keyword')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-row mb-20">
                                    <div class="col-sm-4">
                                        <label class="font-14 bold black">Meta Description </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <textarea name="meta_description" class="form-control"  maxlength="255">  {{$category->meta_description}}</textarea>
                                        @error('meta_description')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <a href="{{route('admin.categories.index')}}" class="btn  btn-danger mt-4 pr-4 pl-4">Back</a>
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
