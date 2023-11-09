




@php

//  $usr=Auth::guard('admin')->user();
// dd($usr);
@endphp



    <!--**********************************
            Nav header start
        ***********************************-->
        <div class="nav-header">
            <a href="{{route('admin.dashboard')}}" class="brand-logo">
                {{-- <img class="logo-abbr" src="{{asset('assets/images/logo.png')}}" alt=""> --}}
                {{-- <img class="logo-compact" src="{{asset('assets/images/logo-text.png')}}" alt=""> --}}
                <img class="brand-title" src="{{asset('assets/images/sm-online.png')}}" alt="">
                {{-- <img class="logo-abbr" src="{{asset('assets/images/sm-online.png')}}" alt=""> --}}
            </a>

            <div class="nav-control">
                <div class="hamburger">
                    <span class="line"></span><span class="line"></span><span class="line"></span>
                </div>
            </div>
        </div>
        <!--**********************************
            Nav header end
        ***********************************-->

        <div class="header">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-between">
                        <div class="header-left">
                            {{-- <div class="search_bar dropdown">
                                <span class="search_icon p-3 c-pointer" data-toggle="dropdown">
                                    <i class="mdi mdi-magnify"></i>
                                </span>
                                <div class="dropdown-menu p-0 m-0">
                                    <form>
                                        <input class="form-control" type="search" placeholder="Search" aria-label="Search">
                                    </form>
                                </div>
                            </div> --}}
                        </div>

                        <ul class="navbar-nav header-right">
                            <li class="nav-item dropdown notification_dropdown">
                                <a class="nav-link" href="#" role="button" data-toggle="dropdown">
                                    <i class="mdi mdi-bell"></i>
                                    <div class="pulse-css"></div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <ul class="list-unstyled">
                                        <li class="media dropdown-item">
                                            <span class="success"><i class="ti-user"></i></span>
                                            <div class="media-body">
                                                <a href="#">
                                                    <p>
                                                        Admin Profile Updated
                                                    </p>
                                                </a>
                                            </div>
                                            <span class="notify-time">1:19 pm</span>
                                        </li>

                                    </ul>
                                    <a class="all-notification" href="#">See all notifications <i
                                            class="ti-arrow-right"></i></a>
                                </div>
                            </li>
                            <li class="nav-item dropdown header-profile">
                                <a class="nav-link" href="#" role="button" data-toggle="dropdown">
                                    <i class="mdi mdi-account"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    {{-- check permission --}}

                                    @if ($usr&&$usr->can('profile.view'))
                                    <a href="{{route('admin.profile')}}" class="dropdown-item">
                                        <i class="icon-user"></i>
                                        <span class="ml-2">Profile </span>
                                    </a>

                                    @endif
                                    {{-- check permission --}}



                                    <a class="dropdown-item" href="{{ route('admin.logout.submit') }}"
                                    onclick="event.preventDefault();
                                                  document.getElementById('admin-logout-form').submit();">
                                        <i class="icon-key"></i>
                                        <span class="ml-2">Logout </span>
                                    </a>

                                    <form id="admin-logout-form" action="{{ route('admin.logout.submit') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>




                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
