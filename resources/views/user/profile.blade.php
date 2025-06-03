@extends('layouts.app')

@section('title', __('messages.profile'))

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-3">
            <div class="card mb-4">
                <div class="card-body text-center">
                    @if($user->profile_photo)
                        <img src="{{ asset('storage/profile_photos/' . $user->profile_photo) }}" alt="{{ $user->name }}" class="rounded-circle img-fluid" style="width: 150px;">
                    @else
                        <img src="{{ asset('assets/images/user-placeholder.png') }}" alt="{{ $user->name }}" class="rounded-circle img-fluid" style="width: 150px;">
                    @endif
                    <h5 class="my-3">{{ $user->name }}</h5>
                    <p class="text-muted mb-1">{{ $user->email }}</p>
                    <p class="text-muted mb-4">{{ $user->mobile }}</p>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                            <a href="{{ route('user.dashboard') }}" class="text-decoration-none text-dark">{{ __('messages.dashboard') }}</a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                            <a href="{{ route('user.orders') }}" class="text-decoration-none text-dark">{{ __('messages.orders') }}</a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                            <a href="{{ route('user.addresses') }}" class="text-decoration-none text-dark">{{ __('messages.addresses') }}</a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                            <a href="{{ route('user.wishlist') }}" class="text-decoration-none text-dark">{{ __('messages.wishlist') }}</a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                            <a href="{{ route('user.reviews') }}" class="text-decoration-none text-dark">{{ __('messages.reviews') }}</a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                            <a href="{{ route('user.change-password') }}" class="text-decoration-none text-dark">{{ __('messages.change_password') }}</a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                            <a href="{{ route('user.activities') }}" class="text-decoration-none text-dark">{{ __('messages.activities') }}</a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                            <a href="{{ route('logout') }}" class="text-decoration-none text-dark" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                {{ __('messages.logout') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="card mb-4">
                <div class="card-header">{{ __('messages.edit_profile') }}</div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('user.profile.update') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('messages.name') }}</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('messages.email') }}</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="mobile" class="form-label">{{ __('messages.mobile') }}</label>
                            <input type="text" class="form-control @error('mobile') is-invalid @enderror" id="mobile" name="mobile" value="{{ old('mobile', $user->mobile) }}" required>
                            @error('mobile')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="profile_photo" class="form-label">{{ __('messages.profile_photo') }}</label>
                            <input type="file" class="form-control @error('profile_photo') is-invalid @enderror" id="profile_photo" name="profile_photo">
                            @error('profile_photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">{{ __('messages.update_profile') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
