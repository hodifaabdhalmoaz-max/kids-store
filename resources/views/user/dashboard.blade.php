@extends('layouts.app')

@section('title', __('messages.dashboard'))

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
                    <div class="d-flex justify-content-center mb-2">
                        <a href="{{ route('user.profile') }}" class="btn btn-primary">{{ __('messages.edit_profile') }}</a>
                    </div>
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
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $totalOrders }}</h5>
                            <p class="card-text">{{ __('messages.total_orders') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $pendingOrders }}</h5>
                            <p class="card-text">{{ __('messages.pending_orders') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $completedOrders }}</h5>
                            <p class="card-text">{{ __('messages.completed_orders') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $wishlistCount }}</h5>
                            <p class="card-text">{{ __('messages.wishlist_items') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('messages.recent_orders') }}</h5>
                    <a href="{{ route('user.orders') }}" class="btn btn-sm btn-outline-primary">{{ __('messages.view_all') }}</a>
                </div>
                <div class="card-body">
                    @if($recentOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>{{ __('messages.order_id') }}</th>
                                        <th>{{ __('messages.date') }}</th>
                                        <th>{{ __('messages.status') }}</th>
                                        <th>{{ __('messages.total') }}</th>
                                        <th>{{ __('messages.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                        <tr>
                                            <td>#{{ $order->order_number }}</td>
                                            <td>{{ $order->created_at->format('d M Y') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $order->status == 'delivered' ? 'success' : ($order->status == 'ordered' ? 'warning' : 'info') }}">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $order->total }}</td>
                                            <td>
                                                <a href="{{ route('user.order.details', $order->id) }}" class="btn btn-sm btn-primary">{{ __('messages.view') }}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p>{{ __('messages.no_orders_yet') }}</p>
                            <a href="{{ route('shop.index') }}" class="btn btn-primary">{{ __('messages.start_shopping') }}</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
