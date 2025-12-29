@extends('admin.layouts.admin_auth')
@section('content')

<div id="containerbar" class="containerbar">
    <div class="container">
        <div class="auth-box login-box">
            <div class="row no-gutters align-items-center justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="auth-box-right">
                        <div class="card">
                            <div class="card-body" style="box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
                                <form method="POST" action="{{ route('admin.authenticate') }}" autocomplete="off">
                                    @csrf
                                    <div class="form-head mb-4">
                                        <img src="{{ asset('assets/admin/images/logo.png') }}" class="img-fluid" alt="logo">
                                    </div>
                                    <div class="form-group mb-4">
                                        <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Email" required>
                                        @error('email')
                                        <span class="text-danger">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-4">
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Password" required>
                                        @error('password')
                                        <span class="text-danger">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-success btn-lg btn-block font-18">Login</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
