@extends('admin.dashboard.layouts.master')

@section('styles')
    <style>
        .error {
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
                    <h4> Vouchers </h4>
                    <p class="mb-0">Lorem ipsum dolor sit amet consectetur adipisicing elit. Nulla, temporibus.</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Admin</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Vouchers</a></li>
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
                        <h4 class="card-title">Create Voucher</h4>
                        <div class="d-sm-flex justify-content-between">
                            <a href="{{ route('admin.brands.index') }}" class="btn-sm btn-success text-white">All Vouchers</a>
                        </div>
                    </div>
                    <div class="card-body">
                        {{-- @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif --}}

                        <div class="col-lg-8">

                            <form action="{{ route('admin.vouchers.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="form-row mb-20">
                                    <div class="col-sm-4">
                                        <label class="font-14 bold black">Voucher Code </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="code" class="form-control" placeholder="Type here" maxlength="255">
                                    </div>
                                    @error('code')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>


                                <div class="form-row mb-20">
                                    <div class="col-sm-4">
                                        <label class="font-14 bold black ">Discount </label>
                                    </div>
                                    <div class="col-sm-4 mb-30">
                                        <input type="text" name="discount_amount" class="form-control" placeholder="0.00"
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
                                    <div class="col-sm-4">
                                        <label class="font-14 bold black"> Usage Limit</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="usage_limit" class="form-control" placeholder="" maxlength="255">
                                    </div>
                                    @error('usage_limit')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>



                                <div class="form-row mb-20">
                                    <div class="col-sm-4">
                                        <label class="font-14 bold black">Expiry Date</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="date"  name="expires_at" class="form-control">
                                    </div>

                                    @error('featured')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>








                                <a href="{{ route('admin.vouchers.index') }}" class="btn  btn-danger mt-4 pr-4 pl-4">Back</a>
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


        // $(document).ready(function() {
        //     var nameField = $('input[name="name"]');
        //     var slugField = $('input[name="slug"]');

        //     nameField.on('input', function() {
        //         var nameValue = nameField.val();
        //         var slugValue = slugify(nameValue);
        //         slugField.val(slugValue);
        //     });

        //     function slugify(text) {
        //         return text.toString().toLowerCase()
        //             .replace(/\s+/g, '-')
        //             .replace(/[^\w\-]+/g, '')
        //             .replace(/\-\-+/g, '-')
        //             .replace(/^-+/, '')
        //             .replace(/-+$/, '');
        //     }
        // });
    </script>
@endsection
