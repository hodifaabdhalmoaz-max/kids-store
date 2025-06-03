@extends('layouts.app')

@section('title', __('messages.change_password'))

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-3">
            <div class="card mb-4">
                <div class="card-body text-center">
                    @if(Auth::user()->profile_photo)
                        <img src="{{ asset('storage/profile_photos/' . Auth::user()->profile_photo) }}" alt="{{ Auth::user()->name }}" class="rounded-circle img-fluid" style="width: 150px;">
                    @else
                        <img src="{{ asset('assets/images/user-placeholder.png') }}" alt="{{ Auth::user()->name }}" class="rounded-circle img-fluid" style="width: 150px;">
                    @endif
                    <h5 class="my-3">{{ Auth::user()->name }}</h5>
                    <p class="text-muted mb-1">{{ Auth::user()->email }}</p>
                    <p class="text-muted mb-4">{{ Auth::user()->mobile }}</p>
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
                <div class="card-header">{{ __('messages.change_password') }}</div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('user.update-password') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="current_password" class="form-label">{{ __('messages.current_password') }}</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('messages.new_password') }}</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">{{ __('messages.confirm_password') }}</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>

                        <button type="submit" class="btn btn-primary">{{ __('messages.update_password') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
