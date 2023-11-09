@extends('admin.dashboard.layouts.master')

@section('styles')

    <style>
        .error {
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
                    <h4> Brands </h4>
                    <p class="mb-0">Manage and organize all the brands .</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Admin</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Brands</a></li>
                </ol>
            </div>
        </div>

        {{-- @if (session('success'))
            <div class="alert alert-success">xcxv{{ session('success') }}</div>
        @endif --}}
        <div class="row">
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Product Brand</h4>
                        <div class="d-sm-flex justify-content-between">
                            <a href="{{ route('admin.brands.index') }}" class="btn-sm btn-success text-white">All Brands</a>
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

                            <form action="{{route('admin.brands.update',$brand->id)}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')


                                <div class="form-row mb-20">
                                    <div class="col-sm-4">
                                        <label class="font-14 bold black">Name </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="name" class="form-control" value="{{$brand->name}}" placeholder="Type here">
                                    </div>
                                    @error('name')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>




                                <div class="form-row mb-20">
                                    <div class="col-sm-4">
                                        <label class="font-14 bold black">Logo </label>
                                    </div>
                                    <div class="col-sm-8">

                                        {{-- {{$product->thumbnail}} --}}
                                    <div class="d-flex flex-wrap gap-10 mb-3">
                                        @if ($brand->logo)
                                        <div class="preview-image-wrapper">
                                            <img class="preview_image" src="{{ asset('storage/uploads/brands/' . $brand->logo) }}" alt="{{$brand->name}}">
                                            {{-- <img src="{{asset('public/uploads/product/thumbnail/'.$product->thumbnail)}}" width="150" class="preview_image"
                                            id="thumbnail_image_preview" alt="{{$product->name}}"> --}}

                                        </div>

                                        @else
                                        <div class="preview-image-wrapper">
                                            <img src="{{asset('assets/images/brandLogo.png')}}" width="150" class="preview_image" id="thumbnail_image_preview" alt="{{$brand->name}}">

                                        </div>

                                        @endif

                                </div>

                                        <input type="file" name="logo" class="form-control-file">

                                    </div>
                                    @error('logo')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>

                                <div class="form-row mb-20">
                                    <div class="col-sm-4">
                                        <label class="font-14 bold black">Featured Brand </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="checkbox" value="{{$brand->featured}}" {{$brand->featured==1?'checked':''}} name="featured" class="form-control-check">
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


                                        <input type="text" name="meta_title" class="form-control" value="{{$brand->meta_title}}" placeholder="">




                                    @error('meta_title')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                    </div>
                                </div>

                                <div class="form-row mb-20">
                                    <div class="col-sm-4">
                                        <label class="font-14 bold black">Meta Keyword </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="meta_keyword" value="{{$brand->meta_keyword}}" class="form-control">
                                        @error('meta_keyword')
                                        <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-row mb-20">
                                    <div class="col-sm-4">
                                        <label class="font-14 bold black">Meta Description </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <textarea name="meta_description" class="form-control"> {{$brand->meta_description}}</textarea>
                                        @error('meta_description')
                                        <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
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

            reader.addEventListener("load", function() {
                preview.src = reader.result;
            }, false);

            if (file) {
                reader.readAsDataURL(file);
            }
        }

        const fileUpload = document.querySelector('input[type=file]');
        fileUpload.addEventListener('change', previewFile);

        // var tagsInput = document.getElementById('tags-input');

        //     $(tagsInput).tagsinput({
        //     confirmKeys: [13, 188]
        //     });
    </script>

@endsection
