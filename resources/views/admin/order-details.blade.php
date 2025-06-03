@extends('layouts.admin')
@section('content')
<div class="main-content-wrap">
    <div class="tf-section-2 mb-30">
        <div class="wg-box">
            <div class="flex items-center justify-between gap10 flex-wrap">
                <h5>Order #{{ $order->id }} Details</h5>
                <a href="{{ route('admin.orders') }}" class="tf-button style-1">
                    <i class="icon-arrow-left"></i> Back to Orders
                </a>
            </div>
            
            @if(Session::has('status'))
            <div class="alert alert-success mt-3">
                {{Session::get('status')}}
            </div>
            @endif
            
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">Order Information</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th>Order ID:</th>
                                    <td>#{{ $order->id }}</td>
                                </tr>
                                <tr>
                                    <th>Order Date:</th>
                                    <td>{{ $order->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>Order Status:</th>
                                    <td>
                                        @if($order->status == 'ordered')
                                            <span class="badge bg-warning">Ordered</span>
                                        @elseif($order->status == 'delivered')
                                            <span class="badge bg-success">Delivered</span>
                                        @elseif($order->status == 'canceled')
                                            <span class="badge bg-danger">Canceled</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Payment Method:</th>
                                    <td>{{ $order->transaction ? ucfirst($order->transaction->mode) : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Payment Status:</th>
                                    <td>
                                        @if($order->transaction)
                                            @if($order->transaction->status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($order->transaction->status == 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @elseif($order->transaction->status == 'declined')
                                                <span class="badge bg-danger">Declined</span>
                                            @elseif($order->transaction->status == 'refunded')
                                                <span class="badge bg-info">Refunded</span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">Customer Information</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th>Name:</th>
                                    <td>{{ $order->name }}</td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td>{{ $order->phone }}</td>
                                </tr>
                                <tr>
                                    <th>Address:</th>
                                    <td>{{ $order->address }}</td>
                                </tr>
                                <tr>
                                    <th>City:</th>
                                    <td>{{ $order->city }}, {{ $order->state }}, {{ $order->country }}</td>
                                </tr>
                                <tr>
                                    <th>Zip Code:</th>
                                    <td>{{ $order->zip }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Order Items</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                <tr>
                                    <td>
                                        @if($item->product)
                                            <div class="d-flex align-items-center">
                                                @if($item->product->image)
                                                <img src="{{ asset('uploads/products/thumbnails/' . $item->product->image) }}" 
                                                    alt="{{ $item->product->name }}" class="me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                                @endif
                                                <div>
                                                    <h6 class="mb-0">{{ $item->product->name }}</h6>
                                                    <small class="text-muted">SKU: {{ $item->product->SKU }}</small>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">Product no longer available</span>
                                        @endif
                                    </td>
                                    <td>${{ $item->price }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                    <td>${{ $order->subtotal }}</td>
                                </tr>
                                @if($order->discount > 0)
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Discount:</strong></td>
                                    <td>-${{ $order->discount }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Tax:</strong></td>
                                    <td>${{ $order->tax }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td>${{ $order->total }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Update Order Status</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.order.update.status', ['id' => $order->id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row align-items-end">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Order Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="ordered" {{ $order->status == 'ordered' ? 'selected' : '' }}>Ordered</option>
                                        <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                        <option value="canceled" {{ $order->status == 'canceled' ? 'selected' : '' }}>Canceled</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <button type="submit" class="tf-button style-1">Update Status</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
