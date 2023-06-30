@extends('layouts.app')
@section('content')
    <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
            <div class="col-lg-4 mx-auto">
                <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                    <div class="brand-logo">
                        <img src="{{ url('assets/images/logo.svg') }}" alt="logo">
                    </div>
                    <h4>Hello! Sign in to continue.</h4>
                    @if (session()->has('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <h6 class="fw-light">Please enter email and password.</h6>
                    <form class="pt-3" method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email"
                                class="form-control form-control-lg @error('email')
                            is-invalid
                        @enderror"
                                id="exampleInputEmail1" placeholder="Username" value="{{ old('email') }}" required
                                autocomplete="email" autofocus name="email">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input type="password"
                                class="form-control form-control-lg @error('password')
                            is-invalid
                        @enderror"
                                id="exampleInputPassword1" placeholder="Password" name="password" required
                                autocomplete="current-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn"
                                type="submit">SIGN IN</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
