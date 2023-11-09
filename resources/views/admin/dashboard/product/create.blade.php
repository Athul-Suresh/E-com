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

        button.btn-link {
            background-color: transparent;
            padding: 0;
            color: #6045E2;
            -webkit-box-shadow: none !important;
            box-shadow: none !important;
        }

        .btn-link {
            font-weight: 400;
            color: #007bff;
            text-decoration: none;
        }
        .bootstrap-tagsinput{
        width: 100%;
    }
    </style>

@endsection

@section('admin-content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">

            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4> Products </h4>
                    <p class="mb-0">View and manage all products in your inventory from a centralized dashboard.</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Admin</a></li>
                    <li class="breadcrumb-item active"><a href="{{ route('admin.products.index') }}">Products</a></li>
                </ol>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-success">{{ session('error') }}</div>
        @endif

        {{-- <div class="">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div> --}}

        <form action="{{route('admin.products.store')}}" novalidate method="post" enctype="multipart/form-data" id="productForm">
        <div class="row">
                @csrf
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Add Product</h4>
                        <div class="d-sm-flex justify-content-between">
                            <a href="{{ route('admin.products.index') }}" class="btn-sm btn-success text-white">All
                                Products</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="form-row mb-20">
                            <div class="col-sm-3">
                                <label class="font-14 bold black">Product Name <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" name="name" class="form-control" placeholder="Product Name"
                                    value="{{old('name')}}" required>
                                    @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>


                        <div class="form-row mb-20">
                            <div class="col-sm-3">
                                <label class="font-14 bold black ">Categories <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-sm-9">

                                <select class="form-control" name="categories[]" multiple>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ in_array($category->id, old('categories', [])) ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>

                                @error('categories')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-3">
                                <label class="font-14 bold black ">Brand <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-sm-9">
                                <select class="form-control" name="brand">
                                    <option value="">Choose Brand</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}"  {{ $brand->id==old('brand') ? 'selected' : '' }}>{{ $brand->name }}</option>
                                    @endforeach
                                </select>

                                @error('brand')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-3">
                                <label class="font-14 bold black ">Unit <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-sm-9">
                                <select class=" form-control" name="unit">
                                    @foreach ($units as $unit)
                                    <option value="{{$unit->id}}" {{ $unit->id==old('unit') ? 'selected' : '' }}> {{$unit->name}} </option>
                                    @endforeach
                                </select>
                                @error('unit')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-3">
                                <label class="font-14 bold black ">Condition <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-sm-9">
                                <select class="form-control" name="condition">
                                    <option value="">Choose</option>
                                    @foreach ($conditions as $item)
                                        <option value="{{$item->id}}" {{ $item->id==old('condition') ? 'selected' : '' }}> {{$item->name}} </option>
                                    @endforeach
                                </select>
                                @error('condition')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-3">
                                <label class="font-14 bold black ">Voucher </label>
                            </div>
                            <div class="col-sm-9">
                                <select class="form-control" name="voucher">
                                    <option value="">Choose</option>
                                    @foreach ($vouchers as $voucher)
                                        <option value="{{$voucher->id}}" {{ $voucher->id==old('voucher') ? 'selected' : '' }}> {{$voucher->voucher_code}} </option>
                                    @endforeach
                                </select>
                                @error('voucher')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            </div>
                        </div>
                        {{-- <div class="form-row mb-20">
                            <div class="col-sm-3">
                                <label class="font-14 bold black ">Tags </label>
                            </div>
                            <div class="col-sm-9">
                                <select class="form-control select2" name="tags[]" multiple>
                                    @foreach ($tags as $tag)
                                        <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> --}}
                        <div class="form-row mb-20">
                            <div class="col-sm-3">
                                <label class="font-14 bold black ">Discount </label>
                            </div>
                            <div class="col-sm-5 mb-30">
                                <input type="number" name="discount_amount" class="form-control" min="0.00" placeholder="0.00"
                                    value="{{old('discount_amount')}}">
                                    @error('discount_amount')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-sm-4">
                                <select class="form-control" name="discount_amount_type">
                                    <option value="1" {{old('discount_amount_type')==1?'selected':''}} > Percentage</option>
                                    <option value="2" {{old('discount_amount_type')==2?'selected':''}} > Flat</option>
                                </select>
                                @error('discount_amount_type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror

                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-3">
                                <label class="font-14 bold black ">Purchase Price <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-sm-5 mb-30">
                                <input type="number" name="purchase_price" class="form-control" min="0.00"
                                    value="{{old('purchase_price')||0.00}}"
                                    placeholder="0.00"
                                    >
                                    @error('purchase_price')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-3">
                                <label class="font-14 bold black ">Unit Price <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-sm-5 mb-30">
                                <input type="number" name="unit_price" class="form-control" min="0.00" placeholder="0.00"
                                    value="{{old('unit_price')}}">
                                    @error('unit_price')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-3">
                                <label class="font-14 bold black ">Offer Price </label>
                            </div>
                            <div class="col-sm-5 mb-30">
                                <input type="number" name="offer_price" class="form-control" min="0.00" placeholder="0.00"
                                    value="{{old('offer_price')}}">
                                    @error('offer_price')
                                    <span class="text-danger">{{ $message }}</span>

                                    @enderror
                            </div>

                        </div>

                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Product Description</h4>

                    </div>
                    <div class="card-body">
                        <div class="form-row mb-20">
                            <div class="col-sm-3">
                                <label class="font-14 bold black ">Summary <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-sm-9">
                                <div class="editor-wrap">
                                    <textarea class="form-control" name="summary" maxlength="500">{{old('summary')}}</textarea>
                                </div>
                                @error('summary')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-3">
                                <label class="font-14 bold black ">Description <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-sm-9">
                                <div class="editor-wrap">
                                    <textarea id="description-ck" class="form-control ckeditor" name="description">{{old('description')}}</textarea>
                                    @error('description')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-30">
                    <div class="card-header bg-white border-bottom2">
                        <div class="d-sm-flex justify-content-between align-items-center">
                            <h4>Product Images</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-row mb-20">
                            <div class="col-sm-3">
                                <label class="font-14 bold black mb-0">Thumbnail Image <span class="text-danger">*</span></label>
                                <p>500*500</p>
                            </div>
                            <div class="col-sm-">
                                <input type="hidden" name="thumbnail_image" id="thumbnail_image_id" value="">
                                <div class="image-box">
                                    <div class="d-flex flex-wrap gap-10 mb-3">
                                        <div class="preview-image-wrapper" >
                                            <img src="{{ asset('assets/images/brandLogo.png') }}" alt="Thumbnail Image"
                                                width="150" class="preview_image" id="thumbnail_image_preview">

                                        </div>

                                    </div>
                                    <div class="image-box-actions">
                                        <input type="file" name="thumbnail" id="thumbnail"class="form-control-file">
                                    </div>
                                    @error('thumbnail')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-row mb-20 product-gallery-images">
                            <div class="col-sm-3">
                                <label class="font-14 bold black mb-0">Gallery Images <span class="text-danger">*</span></label>
                                <p>500*500</p>
                            </div>
                            <div class="col-sm-9">

                                <div class="d-flex flex-wrap gap-10 mb-3">
                                    <div class="preview-image-wrapper" id="preview-image-wrapper">
                                        <img src="{{ asset('assets/images/brandLogo.png') }}" alt="Gallery Images"
                                            width="150" class="preview_image mr-2" id="thumbnail_image_preview">



                                    </div>

                                </div>
                                <div class="image-box-actions">
                                    <input type="file" id="file-input-gallery" name="gallery[]" multiple class="form-control-file">

                                    @error('gallery[]')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Product Seo information-->
                <div class="card mb-30">
                    <div class="card-header bg-white border-bottom2">
                        <div class="d-sm-flex justify-content-between align-items-center">
                            <h4>Seo Meta Tags</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-row mb-20">
                            <div class="col-sm-3">
                                <label class="font-14 bold black ">Meta Title </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" name="meta_title" class="form-control"
                                    placeholder="Type here" value="{{old('meta_title')}}">
                                    @error('meta_title')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                            </div>
                        </div>

                        <div class="form-row mb-20">
                            <div class="col-sm-3">
                                <label class="font-14 bold black ">Meta Keywords </label>
                            </div>
                            <div class="col-sm-9">
                                <input type='text' class="form-control"
                                data-role="tagsinput"

                                name="meta_keyword" value="{{old('meta_keyword')}}" data-prevent-default="true">

                                @error('meta_keyword')

                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>




                        <div class="form-row mb-20">
                            <div class="col-sm-3">
                                <label class="font-14 bold black ">Meta Description </label>
                            </div>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="meta_description">{{old('meta_description')}}</textarea>
                                @error('meta_description')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>
                <!--End product seo information-->

            </div>

            <div class="col-lg-4">
                <!--Featured-->
                <div class="card mb-30">
                    <div class="card-header bg-white border-bottom2">
                        <div class="d-sm-flex justify-content-between align-items-center">
                            <h4>More</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-row mb-20">
                            <div class="col-sm-6">
                                <label class="font-14 bold black ">Featured </label>
                            </div>
                            <div class="col-sm-6">
                                <label class="switch glow primary medium">
                                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') == 1 ? 'checked' : '' }}>
                                    <span class="control"></span>
                                </label>
                                @error('is_featured')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-row mb-20">
                            <div class="col-sm-6">
                                <label class="font-14 bold black ">COD </label>
                            </div>
                            <div class="col-sm-6">
                                <label class="switch glow primary medium">
                                    <input type="checkbox" name="is_cod"  value="1" {{ old('is_cod') == 1 ? 'checked' : '' }}>
                                    <span class="control"></span>
                                </label>
                                @error('is_cod')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-6">
                                <label class="font-14 bold black ">Refundable </label>
                            </div>
                            <div class="col-sm-6">
                                <label class="switch glow primary medium">
                                    <input type="checkbox" name="is_refundable" value="1"  {{ old('is_refundable') == 1 ? 'checked' : '' }}>
                                    <span class="control"></span>
                                </label>
                                @error('is_refundable')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-6">
                                <label class="font-14 bold black ">Warranty </label>
                            </div>
                            <div class="col-sm-6">
                                <label class="switch glow primary medium">
                                    <input type="checkbox" name="has_warranty" value="1" class="has-warranty" {{ old('has_warranty') == 1 ? 'checked' : '' }}
                                        {{-- onchange="warrantyConfig()" --}}
                                        >
                                    <span class="control"></span>
                                </label>
                                @error('has_warranty')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <!--End Featured-->


                 <div class="card mb-30">

                    <div class="card-header bg-white border-bottom2">
                        <div class="d-sm-flex justify-content-between align-items-center">
                            <h4>Quantity</h4>
                        </div>
                    </div>
                    <div class="card-body">

                            <div class="form-row mb-20">
                                <label class="font-14 bold black  col-sm-4">Product Stock <span class="text-danger">*</span></label>
                                <input type="text" class="form-control col-9" name="stock" value="{{old('stock')||1}}"
                                min="1" step="1">
                                    @error('stock')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                            </div>

                        <div class="form-row mb-20">
                            <label class="font-14 bold black  col-sm-4">Minimum Quantity <span class="text-danger">*</span></label>
                            <input type="text" class="form-control col-9" name="min_purchase_qty"
                                value="{{old('min_purchase_qty')||1}}" min="1" step="1">
                                @error('min_purchase_qty')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                        </div>
                        <div class="form-row mb-20">
                            <label class="font-14 bold black  col-sm-4">Maximum Quantity <span class="text-danger">*</span></label>
                            <input type="text" class="form-control col-9" value="{{old('max_purchase_qty')||1}}"
                                name="max_purchase_qty" min="1" step="1">
                                @error('max_purchase_qty')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                        </div>
                    </div>
                </div>


            </div>

        </div>
        <div class="d-flex g-3 justify-content-center">
            <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Save</button>
        </div>
    </form>
    </div>
    </div>
@endsection


@section('scripts')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                tags: true
            });
        });

        var editorInstance = CKEDITOR.instances['description-ck'];
        if (editorInstance) {
            editorInstance.destroy();
        }
        CKEDITOR.replace('description-ck');

        function warrantyConfig() {
            "use strict";
            if ($('.has-warranty').is(":checked")) {
                $('.warranty-config').removeClass('d-none')
            } else {
                $('.warranty-config').addClass('d-none')
            }
        }

        // Single Preview
        function previewThumbnail() {
            const preview = document.getElementById('thumbnail_image_preview');
            const file = document.getElementById('thumbnail').files[0];
            const reader = new FileReader();

            reader.addEventListener("load", function() {
                preview.src = reader.result;
            }, false);

            if (file) {
                reader.readAsDataURL(file);
            }
        }
        const fileUpload = document.getElementById('thumbnail');
        fileUpload.addEventListener('change', previewThumbnail);

        // Gallery Preview
        const fileInput = document.getElementById("file-input-gallery");
        fileInput.addEventListener("change", (event) => {
        const files = event.target.files;
        const previewContainer = document.getElementById("preview-image-wrapper");

        // Clear the container before adding new images
        previewContainer.innerHTML = "";

        for (const file of files) {
            const image = document.createElement("img");
            image.src = URL.createObjectURL(file);
            previewContainer.appendChild(image);
        }
        });



    </script>
@endsection
