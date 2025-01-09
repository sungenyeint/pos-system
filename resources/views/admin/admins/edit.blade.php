@extends('admin.layouts.main')
@section('title', 'Admin Management | Edit')
@section('content')

<div class="contentbar">
    <form class="form-update" action="{{ route('admin.admins.update', $admin->id) }}" method="POST" autocomplete="off">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-5">
                    <div class="card-header">
                        <h5 class="card-title">Admin Edit</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="name">Name<span class="required">*</span></label>
                                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $admin->name) }}" maxlength="{{ config('const.default_text_maxlength') }}" placeholder="Name" required>
                                    @error('name')
                                        <div id="name-error" class="invalid-feedback animated fadeInDown">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="email">Email<span class="required">*</span></label>
                                    <input type="text" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $admin->email) }}" placeholder="Email" required>
                                    @error('email')
                                        <div id="email-error" class="invalid-feedback animated fadeInDown">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="password">Password<span class="required">*</span></label>
                                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" value="" placeholder="Password">
                                    @error('password')
                                        <div id="password-error" class="invalid-feedback animated fadeInDown">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <a href="{{ route('admin.admins.index') }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection
