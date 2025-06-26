@extends('layouts.admin')
@section('content')
<div class="main-content-wrap">
    <div class="tf-section-2 mb-30">
        <div class="wg-box">
            <div class="flex items-center justify-between gap10 flex-wrap">
                <h5>الطلبات</h5>
                <div class="wg-filter flex-grow">
                    <form class="form-search" method="GET" action="{{ route('admin.orders') }}">
                        <fieldset class="name">
                            <input type="text" placeholder="البحث برقم الطلب أو اسم العميل..." class="" name="search"
                                tabindex="2" value="{{ request('search') }}" aria-required="true">
                        </fieldset>
                        <div class="button-submit">
                            <button class="" type="submit"><i class="icon-search"></i></button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="wg-table table-all-user">
                <div class="table-responsive">
                    @if(Session::has('status'))
                    <p class="alert alert-success">{{Session::get('status')}}</p>
                    @endif
                    @if(Session::has('error'))
                    <p class="alert alert-danger">{{Session::get('error')}}</p>
                    @endif
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 80px">رقم الطلب</th>
                                <th>العميل</th>
                                <th class="text-center">الهاتف</th>
                                <th class="text-center">المجموع الفرعي</th>
                                <th class="text-center">الضريبة</th>
                                <th class="text-center">الإجمالي</th>
                                <th class="text-center">الحالة</th>
                                <th class="text-center">تاريخ الطلب</th>
                                <th class="text-center">العناصر</th>
                                <th class="text-center">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            <tr>
                                <td class="text-center">{{$order->id}}</td>
                                <td>{{$order->name}}</td>
                                <td class="text-center">{{$order->phone}}</td>
                                <td class="text-center">${{$order->subtotal}}</td>
                                <td class="text-center">${{$order->tax}}</td>
                                <td class="text-center">${{$order->total}}</td>
                                <td class="text-center">
                                    @if($order->status == 'ordered')
                                        <span class="badge bg-warning">مطلوب</span>
                                    @elseif($order->status == 'delivered')
                                        <span class="badge bg-success">مسلم</span>
                                    @elseif($order->status == 'canceled')
                                        <span class="badge bg-danger">ملغي</span>
                                    @endif
                                </td>
                                <td class="text-center">{{$order->created_at->format('Y-m-d H:i:s')}}</td>
                                <td class="text-center">{{$order->orderItems->count()}}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.order.details', $order) }}">
                                        <div class="list-icon-function view-icon">
                                            <div class="item eye">
                                                <i class="icon-eye"></i>
                                            </div>
                                        </div>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center">لا توجد طلبات</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                    {{$orders->links('pagination::bootstrap-5')}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
