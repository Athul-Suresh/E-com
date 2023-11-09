@php
    // Dynamic Area
    $usr = auth('admin')->user();
    // dd($usr);
@endphp


<div class="quixnav">
    <div class="quixnav-scroll">
        <ul class="metismenu" id="menu">
            <li class="nav-label first">Admin</li>
            {{--
                    <ul class="sidebar-menu">
                        @foreach ($menu_items as $item)
                          <li>
                            <a href="{{ $item->url }}">
                              {{ $item->label }}
                            </a>
                            @if ($item->children)
                              <ul>
                                @foreach ($item->children as $child)
                                  <li>
                                    <a href="{{ $child->url }}">
                                      {{ $child->label }}
                                    </a>
                                  </li>
                                @endforeach
                              </ul>
                            @endif
                          </li>
                        @endforeach
                      </ul> --}}




            @if ($usr&&$usr->can('dashboard.view'))
                <li><a href="{{ route('admin.dashboard') }}" aria-expanded="false"><i class="icon icon-globe-2"></i><span
                            class="nav-text">Home</span></a></li>
            @endif

            @if (
                $usr&&$usr->can('role.create') ||
                    $usr&&$usr->can('role.view') ||
                    $usr&&$usr->can('role.edit') ||
                    $usr&&$usr->can('role.delete') ||
                    $usr&&$usr->can('admin.create') ||
                    $usr&&$usr->can('admin.view') ||
                    $usr&&$usr->can('admin.edit') ||
                    $usr&&$usr->can('admin.delete'))
                <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i
                            class="icon icon-app-store"></i><span class="nav-text">User Management</span></a>
                    <ul aria-expanded="false">
                        @if ($usr&&$usr->can('role.view'))
                            <li><a href="{{ route('admin.roles.index') }}">Role</a></li>
                        @endif
                        @if ($usr&&$usr->can('admin.view'))
                            <li><a href="{{ route('admin.admins.index') }}">Admin</a></li>
                        @endif

                    </ul>
                </li>

            @endif


            <li class="nav-label">E-Commerce</li>


            <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">
                    <i class="fa fa-th"></i>
                    <span class="nav-text">Products</span></a>
                <ul aria-expanded="false">

                    @if ($usr&&$usr->can('brand.view'))
                        <li><a href="{{ route('admin.brands.index') }}">Brands</a></li>
                    @endif

                    @if ($usr&&$usr->can('category.view'))
                        <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">Categories</a>
                            <ul aria-expanded="false">
                                <li><a href="{{ route('admin.maincategories.index') }}">Main Category</a></li>
                                {{-- <li><a href="{{route('admin.categories.index')}}">Sub Category</a></li> --}}

                            </ul>
                        </li>
                    @endif

                    @if ($usr&&$usr->can('unit.view'))
                        <li><a href="{{ route('admin.units.index') }}">Units</a></li>
                    @endif
                    @if ($usr&&$usr->can('productTag.view'))
                        <li><a href="{{ route('admin.productTags.index') }}">Products Tags</a></li>
                    @endif
                    @if ($usr&&$usr->can('productCondition.view'))
                        <li><a href="{{ route('admin.productConditions.index') }}">Product Condition</a></li>
                    @endif
                    @if ($usr&&$usr->can('product.view'))
                        <li><a href="{{ route('admin.products.index') }}">All Products</a></li>
                    @endif
                    @if ($usr&&$usr->can('product.create'))
                        <li><a href="{{ route('admin.products.create') }}">Add New Product</a></li>
                    @endif
                    @if ($usr&&$usr->can('voucher.view'))
                        <li><a href="{{ route('admin.vouchers.index') }}">Voucher</a></li>
                    @endif
                </ul>
            </li>


            @if ($usr&&$usr->can('dashboard.view'))
            <li><a href="{{ route('admin.customers.index') }}" aria-expanded="false"><i class="icon icon-users-mm-2"></i><span
                        class="nav-text">Customers</span></a></li>
            @endif


            {{-- @if ($usr&&$usr->can('review.view'))
            <li>
                <a href="{{route('admin.review.index')}}" aria-expanded="false">
                    <i class="icon icon-heart-2-2"></i>
                    <span class="nav-text">Reviews</span>
                </a>
            </li>
             @endif --}}


            @if ($usr&&$usr->can('order.view'))
            <li>
                <a href="{{route('admin.orders.index')}}" aria-expanded="false">
                    <i class="icon icon-cart-simple"></i>
                    <span class="nav-text">Orders</span>
                </a>
            </li>
            @endif

            @if ($usr&&$usr->can('enquiry.view'))
            <li>
                <a href="{{route('admin.enquiry.index')}}" aria-expanded="false">
                    <i class="icon icon-phone-2"></i>
                    <span class="nav-text">Enquiry</span>
                </a>
            </li>
            @endif



            <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">
                <i class="fa fa-bars"></i>
                <span class="nav-text">Reports</span></a>
            <ul aria-expanded="false">
                {{-- @if ($usr&&$usr->can('unit.view'))
                <li><a href="{{ route('admin.report.product') }}">Product Report</a></li>
                @endif --}}

                @if ($usr&&$usr->can('unit.view'))
                <li><a href="{{ route('admin.report.sales') }}">Sales Report</a></li>
                @endif
                @if ($usr&&$usr->can('unit.view'))
                <li><a href="{{ route('admin.report.stock') }}">Stock Report</a></li>
                @endif


            </ul>


































            {{--
                DB Xeventure
                xeventureadmin_smsoft
                xeventureadmin_smsoft
                VEVBXa%Kle3f

            --}}





            {{-- @if ($usr&&$usr->can('category.create') || $usr&&$usr->can('category.view') || $usr&&$usr->can('category.edit') || $usr&&$usr->can('category.delete'))
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">
                      <i class="fa fa-th"></i>
                      <span class="nav-text">Categories</span></a>
                      <ul aria-expanded="false">

                        @if ($usr&&$usr->can('category.view'))
                        <li><a href="{{ route('admin.categories.index') }}">All Category</a></li>

                        @endif

                        @if ($usr&&$usr->can('category.create'))
                        <li><a href="{{ route('admin.categories.create') }}">Add Category</a></li>
                        @endif

                      </ul>
                    </li>
                    @endif --}}

            {{-- @if ($usr&&$usr->can('brand.create') || $usr&&$usr->can('brand.view') || $usr&&$usr->can('brand.edit') || $usr&&$usr->can('brand.delete'))
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">
                      <i class="fa fa-th"></i>
                      <span class="nav-text">Brands</span></a>
                      <ul aria-expanded="false">

                        @if ($usr&&$usr->can('brand.view'))
                        <li><a href="{{ route('admin.brands.index') }}">All Brands</a></li>

                        @endif

                        @if ($usr&&$usr->can('brand.create'))
                        <li><a href="{{ route('admin.brands.create') }}">Add Brand</a></li>
                        @endif

                      </ul>
                    </li>
                    @endif --}}
            {{-- <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i
                        class="fa fa-cube"></i><span class="nav-text">Products</span></a>
                        <ul aria-expanded="false">
                        @if ($usr&&$usr->can('product.view'))
                        <li><a href="{{ route('admin.products.index') }}">Products</a></li>
                        @endif

                        <li><a href="{{ route('admin.products.create') }}">Add Product</a></li>
                       <li><a href="{{ route('admin.products.index') }}">Products</a></li> --}}

            {{-- <li><a href="./app-profile.html">Profile</a></li>
                            <li><a href="./app-profile.html">Profile</a></li>

                            <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">Email</a>
                                <ul aria-expanded="false">
                                    <li><a href="./email-compose.html">Compose</a></li>
                                    <li><a href="./email-inbox.html">Inbox</a></li>
                                    <li><a href="./email-read.html">Read</a></li>
                                </ul>
                            </li>
                            <li><a href="./app-calender.html">Calendar</a></li>
                        </ul>
                    </li> --}}
            {{-- <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">
                      <i class="fa fa-shopping-cart"></i>
                      <span class="nav-text">Orders</span></a>
                      <ul aria-expanded="false">

                        <li><a href="#">Order Detail</a></li>
                        <li><a href="#">Invoice</a></li>


                      </ul>
                    </li> --}}




            {{-- <li><a href="#" aria-expanded="false">
                      <i class="fa fa-star-half-o"></i>
                      <span class="nav-text">Reviews</span></a></li> --}}

        </ul>
    </div>
</div>
