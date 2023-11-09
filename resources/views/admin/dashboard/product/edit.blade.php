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

        {{-- @if (session('success'))
            <div class="alert alert-success">xcxv{{ session('success') }}</div>
        @endif --}}

        {{-- <div class="">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div> --}}

        <form action="{{route('admin.products.update',$product->id)}}" method="post" enctype="multipart/form-data">
        <div class="row">
                @csrf
                @method('PUT')
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Update Product</h4>
                        <div class="d-sm-flex justify-content-between">
                            <a href="{{ route('admin.products.index') }}" class="btn-sm btn-success text-white">All
                                Products</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="form-row mb-20">
                            <div class="col-sm-3">
                                <label class="font-14 bold black">Name </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" name="name" class="form-control" placeholder="Product Name"
                                value="{{ old('name') ?: $product->name }}" required>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror

                            </div>
                        </div>


                        <div class="form-row mb-20">
                            <div class="col-sm-3">
                                <label class="font-14 bold black ">Categories </label>
                            </div>
                            <div class="col-sm-9">

                                <select name="categories[]" class="form-control" multiple>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ in_array($category->id, old('categories', $product->mainProductCategories->pluck('id')->toArray())) ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('categories')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-3">
                                <label class="font-14 bold black ">Brand </label>
                            </div>
                            <div class="col-sm-9">
                                <select class="form-control" name="brand">
                                    <option value="">Choose Brand</option>
                                    @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ ($brand->id == old('brand') || $brand->id == $product->brand->id) ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                    @endforeach
                                </select>

                                @error('brand')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-3">
                                <label class="font-14 bold black ">Unit</label>
                            </div>
                            <div class="col-sm-9">
                                <select class="form-control" name="unit">

                                    @foreach ($units as $ut)
                                        <option value="{{ $ut->id }}" {{ ($ut->id == old('unit') || $ut->id == $product->unit->id) ? 'selected' : '' }}>
                                            {{ $ut->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('unit')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-3">
                                <label class="font-14 bold black ">Condition </label>
                            </div>
                            <div class="col-sm-9">
                                <select class="form-control" name="condition">
                                    <option value="">Choose</option>
                                    @foreach ($conditions as $item)
                                        <option value="{{$item->id}}" {{ $item->id==old('condition') || $item->id == $product->condition->id ? 'selected' : '' }}> {{$item->name}} </option>
                                    @endforeach
                                </select>
                                @error('condition')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-3">
                                <label class="font-14 bold black ">voucher </label>
                            </div>
                            <div class="col-sm-9">
                                <select class="form-control" name="voucher">
                                    <option value="">Choose</option>
                                    @foreach ($vouchers as $voucher)
                                    @if ( $product->voucher_id)
                                        <option value="{{$voucher->id}}" {{ $voucher->id==old('condition') || $voucher->id == $product->voucher->id ? 'selected' : '' }}> {{$voucher->voucher_code}} </option>
                                    @else
                                        <option value="{{$voucher->id}}" {{ $voucher->id==old('condition') ? 'selected' : '' }}> {{$voucher->voucher_code}} </option>
                                    @endif
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
                                <select class="form-control select2" multiple name="tags[]">
                                    @foreach ($tags as $tag2)
                                    @if ( $product->tags)
                                    <option value="{{ $tag2->id }}" {{ $tag2->id==old('tags') || $tag2->id == $product->tags->id ? 'selected' : '' }}>{{ $tag2->name }}</option>
                                    @else
                                        <option value="{{ $tag2->id }}">{{ $tag2->name }}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
 --}}









                        <div class="form-row mb-20">
                            <div class="col-sm-3">
                                <label class="font-14 bold black ">Discount </label>
                            </div>
                            <div class="col-sm-5 mb-30">
                                <input type="text" name="discount_amount" class="form-control" placeholder="0.00"
                                    value="{{ old('discount_amount') ?: $product->discount_amount }}">
                                    @error('discount_amount')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-sm-4">
                                <select class="form-control" name="discount_amount_type">
                                    <option value="1" {{old('discount_amount_type')==1||$product->discount_amount_type==1?'selected':''}} > Percentage</option>
                                    <option value="2" {{old('discount_amount_type')==2||$product->discount_amount_type==2?'selected':''}} > Flat</option>
                                </select>
                                @error('discount_amount_type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror

                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-3">
                                <label class="font-14 bold black ">Purchase Price </label>
                            </div>
                            <div class="col-sm-5 mb-30">
                                <input type="text" name="purchase_price" class="form-control" placeholder="0.00"
                                    value="{{ old('purchase_price') ?: $product->purchase_price }} ">
                                    @error('purchase_price')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-3">
                                <label class="font-14 bold black ">Unit Price </label>
                            </div>
                            <div class="col-sm-5 mb-30">
                                <input type="text" name="unit_price" class="form-control" placeholder="0.00"
                                    value="{{ old('unit_price') ?: $product->unit_price }}">
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
                                <input type="text" name="offer_price" class="form-control" placeholder="0.00"
                                    value="{{ old('offer_price') ?: $product->offer_price }}">
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
                                <label class="font-14 bold black ">Summary </label>
                            </div>
                            <div class="col-sm-9">
                                <div class="editor-wrap">
                                    <textarea class="form-control" name="summary" maxlength="500">{{ old('summary') ?: $product->short_description }}</textarea>
                                </div>
                                @error('summary')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-3">
                                <label class="font-14 bold black ">Description </label>
                            </div>
                            <div class="col-sm-9">
                                <div class="editor-wrap">
                                    <textarea id="description-ck" class="form-control ckeditor" name="description">{{ old('description') ?: $product->long_description }}</textarea>
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
                                <label class="font-14 bold black mb-0">Thumbnail Image </label>
                                <p>385x380</p>
                            </div>
                            <div class="col-sm-">
                                <input type="hidden" name="thumbnail_image" id="thumbnail_image_id" value="">
                                <div class="image-box">
                                    {{-- {{$product->thumbnail}} --}}
                                    <div class="d-flex flex-wrap gap-10 mb-3">
                                            @if ($product->thumbnail)
                                            <div class="preview-image-wrapper">
                                                <img class="preview_image" src="{{ asset('storage/uploads/product/thumbnail/' . $product->thumbnail) }}" alt="{{$product->name}}">
                                                {{-- <img src="{{asset('public/uploads/product/thumbnail/'.$product->thumbnail)}}" width="150" class="preview_image"
                                                id="thumbnail_image_preview" alt="{{$product->name}}"> --}}

                                            </div>

                                            @else
                                            <div class="preview-image-wrapper">
                                                <img src="{{asset('assets/images/brandLogo.png')}}" width="150" class="preview_image" id="thumbnail_image_preview" alt="{{$product->name}}">

                                            </div>

                                            @endif

                                    </div>
                                    <div class="image-box-actions">
                                        <input type="file" name="thumbnail" value="{{$product->thumbnail}}" class="form-control-file">
                                    </div>
                                    @error('thumbnail')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-row mb-20 product-gallery-images">
                            <div class="col-sm-3">
                                <label class="font-14 bold black mb-0">Gallery Images </label>
                                <p>624x624</p>
                            </div>
                            <div class="col-sm-9">

                                <div class="d-flex flex-wrap gap-10 mb-3">
                                    @forelse ($product->gallery as $gallerySingle)
                                    <div class="preview-image-wrapper m-1">
                                        <img src="{{ asset('storage/uploads/product/gallery/' . $gallerySingle->image_path) }}"
                                             alt="{{$product->name}}"
                                             width="150"
                                             class="preview_image"
                                             id="thumbnail_image_preview">
                                    </div>
                                @empty
                                    <div class="preview-image-wrapper m-1">
                                        <img src="{{ asset('assets/images/brandLogo.png') }}" alt="{{$product->name}}"
                                             width="150" class="preview_image" id="thumbnail_image_preview">
                                    </div>
                                @endforelse


                                </div>
                                <div class="image-box-actions">
                                    <input type="file" id="gallery" name="gallery[]" multiple class="form-control-file">

                                    @error('gallery')
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
                                    placeholder="Type here" value="{{old('meta_title')?:$product->meta_title}}">
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
                                <input class="form-control"
                                data-role="tagsinput"
                                name="meta_keyword" value="{{old('meta_keyword')?:$product->meta_keyword}}"/>
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
                                <textarea class="form-control" name="meta_description">{{old('meta_description')?:$product->meta_description}}</textarea>
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
                                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ?: ($product->featured ? 'checked' : '') }}>

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
                                    <input type="checkbox" name="is_cod"  value="1"  {{ old('is_cod') ?: ($product->cod ? 'checked' : '') }}>
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
                                    <input type="checkbox" name="is_refundable" value="1"  {{ old('is_refundable') ?: ($product->refundable ? 'checked' : '') }}>
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
                                    <input type="checkbox" name="has_warranty" value="1" class="has-warranty"  {{ old('has_warranty') ?: ($product->warranty ? 'checked' : '') }}
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
                            <input type="number" class="form-control col-9" name="stock" value="{{old('stock')?:$product->stock}}"
                                min="1" step="1">
                                @error('stock')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                        </div>

                    <div class="form-row mb-20">
                        <label class="font-14 bold black  col-sm-4">Minimum Quantity <span class="text-danger">*</span></label>
                        <input type="number" class="form-control col-9" name="min_purchase_qty" min="1" step="1"
                            value="{{old('min_purchase_qty')?:$product->min_qty}}">
                            @error('min_purchase_qty')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                    </div>


                    <div class="form-row mb-20">
                        <label class="font-14 bold black  col-sm-4">Maximum Quantity <span class="text-danger">*</span></label>
                        <input type="number" class="form-control col-9" value="{{old('max_purchase_qty')?:$product->max_qty}}"
                            name="max_purchase_qty" min="1" step="1">
                            @error('max_purchase_qty')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                    </div>

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

        window.onload = function() {
            CKEDITOR.replace('description-ck');
            CKEDITOR.replace('short_description-ck');
        };

        function warrantyConfig() {
            "use strict";
            if ($('.has-warranty').is(":checked")) {
                $('.warranty-config').removeClass('d-none')
            } else {
                $('.warranty-config').addClass('d-none')
            }
        }
    </script>
@endsection
