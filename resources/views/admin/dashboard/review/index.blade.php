@extends('admin.dashboard.layouts.master')

@section('styles')
    <style>
        .list-styled{
            list-style-type: disc!important;
        }
    </style>
@endsection

@section('admin-content')


    <div class="container-fluid">
        <div class="row page-titles mx-0">

            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>All Reviews</h4>
                    <p class="mb-0">Lorem ipsum dolor sit amet consectetur adipisicing elit. Nulla, temporibus.</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Admin</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Review</a></li>
                </ol>
            </div>


        </div>

        @if (Session::has("success"))
        <div class="alert alert-success text-white  alert-dismissible fade show" role="alert">
         {{ Session::get('success') }}
        </div>
        @endif

        @if (Session::has("error"))
        <div class="alert alert-danger text-white  alert-dismissible fade show" role="alert">
         {{ Session::get('error') }}
        </div>
        @endif




        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Manage Review</h4>
                        {{-- <div class="d-sm-flex justify-content-between">
                            <a href="{{ route('admin.orders.create') }}" class="btn-sm btn-success text-white">Add
                                Product</a>
                        </div> --}}
                    </div>
                    <div class="card-body">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table header-border table-responsive-sm">
                                    <thead>
                                        <tr>
                                            <th class="font-weight-bold text-dark">#</th>
                                            <th class="font-weight-bold text-dark">Product</th>
                                            <th class="font-weight-bold text-dark">Rating
                                                <br>
                                                <i class="fa fa-star text-warning"></i>
                                                <i class="fa fa-star text-warning"></i>
                                                <i class="fa fa-star text-warning"></i>
                                                <i class="fa fa-star text-warning"></i>
                                                <i class="fa fa-star text-warning"></i>
                                            </th>


                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($products as $key=> $product)

                                            <tr>
                                                <td>{{$key+1}}</td>
                                                <td>{{ Str::limit($product->name,100)}}</td>
                                                <td>
                                                    @php
                                                        $totalRatings = 0;
                                                        $reviewsCount = count($product->reviews);

                                                        foreach ($product->reviews as $review) {
                                                            $ratingValue = $review['rating'];
                                                            $totalRatings += $ratingValue;
                                                        }

                                                        $averageRating = ($reviewsCount > 0) ? ($totalRatings / $reviewsCount) : 0;
                                                        $filledStars = str_repeat('<i class="fa fa-star text-warning"></i>', $averageRating);
                                                        // $emptyStars = str_repeat('<i class="fa fa-star  border"></i>', 5 - $averageRating);
                                                    @endphp

                                                    {!! $filledStars !!}

                                                </td>

                                            </tr>

                                        @empty

                                            <tr>
                                                <th colspan="8" class="text-center">No Orderd To List</th>
                                            </tr>

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
