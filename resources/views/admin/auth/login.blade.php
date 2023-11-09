@extends('admin.auth.layout')



@section('auth-content')

 <div class="authincation"  style="height:100vh">
    <div class="container h-100">
        <div class="row justify-content-center h-100 align-items-center">
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 ">
                <div class="authincation-content">
                    <div class="row no-gutters">
                        <div class="col-xl-12">
                            <div class="auth-form">
                                <h4 class="text-center mb-4">Sign in</h4>
                                <form method="POST" action="{{ route('admin.login') }}">
                                    @csrf

                                    <div class="form-group">
                                        <label for="email">Email:</label>
                                        <input type="email"  class="form-control" name="email" id="email" value="{{ old('email') }}" required autofocus>
                                        @error('email')
                                            <span role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="password">Password:</label>
                                        <input type="password"  class="form-control" name="password" id="password" required>
                                        @error('password')
                                            <span role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-row d-flex justify-content-between mt-4 mb-2">
                                        <div class="form-group">
                                            <div class="form-check ml-2">
                                                <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                                <label class="form-check-label" for="remember">Remember me</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            {{-- <a href="page-forgot-password.html">Forgot Password?</a> --}}
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary btn-block">Sign me in</button>
                                    </div>
                                </form>

                                {{-- <div class="new-account mt-3">
                                    <p>Don't have an account? <a class="text-primary" href="./page-register.html">Sign up</a></p>
                                </div>--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
