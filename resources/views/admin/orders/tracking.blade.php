@extends('layouts.admin')
@section('content')
<div class="main-content-wrap">
    <div class="tf-section-2 mb-30">
        <div class="wg-box">
            <div class="flex items-center justify-between gap10 flex-wrap">
                <h5>تتبع الطلب #{{ $order->id }}</h5>
                <div class="flex gap10">
                    <a href="{{ route('admin.order.details', $order) }}" class="tf-button style-1 w208">
                        <i class="icon-arrow-left"></i>
                        العودة لتفاصيل الطلب
                    </a>
                </div>
            </div>
            
            <!-- إضافة تتبع جديد -->
            <div class="wg-box mb-30">
                <h6>إضافة تحديث تتبع جديد</h6>
                <form method="POST" action="{{ route('admin.order.tracking.add', $order) }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <fieldset class="name">
                                <div class="body-title">الحالة</div>
                                <select name="status" class="mb-10" required>
                                    <option value="ordered">مطلوب</option>
                                    <option value="processing">قيد المعالجة</option>
                                    <option value="shipped">تم الشحن</option>
                                    <option value="delivered">تم التسليم</option>
                                    <option value="cancelled">ملغي</option>
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-md-4">
                            <fieldset class="name">
                                <div class="body-title">الموقع</div>
                                <input type="text" name="location" class="mb-10" placeholder="الموقع الحالي">
                            </fieldset>
                        </div>
                        <div class="col-md-4">
                            <fieldset class="name">
                                <div class="body-title">&nbsp;</div>
                                <button type="submit" class="tf-button w208">إضافة تحديث</button>
                            </fieldset>
                        </div>
                    </div>
                    <fieldset class="name">
                        <div class="body-title">التعليق</div>
                        <textarea name="comment" class="mb-10" placeholder="تفاصيل التحديث..." required></textarea>
                    </fieldset>
                </form>
            </div>

            <!-- سجل التتبع -->
            <div class="wg-table table-all-user">
                <h6>سجل التتبع</h6>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>التاريخ</th>
                                <th>الحالة</th>
                                <th>الموقع</th>
                                <th>التعليق</th>
                                <th>بواسطة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($order->tracking as $track)
                            <tr>
                                <td>{{ $track->created_at->format('Y-m-d H:i:s') }}</td>
                                <td>
                                    @if($track->status == 'ordered')
                                        <span class="badge bg-warning">مطلوب</span>
                                    @elseif($track->status == 'processing')
                                        <span class="badge bg-info">قيد المعالجة</span>
                                    @elseif($track->status == 'shipped')
                                        <span class="badge bg-primary">تم الشحن</span>
                                    @elseif($track->status == 'delivered')
                                        <span class="badge bg-success">تم التسليم</span>
                                    @elseif($track->status == 'cancelled')
                                        <span class="badge bg-danger">ملغي</span>
                                    @endif
                                </td>
                                <td>{{ $track->location ?? '-' }}</td>
                                <td>{{ $track->comment }}</td>
                                <td>{{ $track->updatedBy->name ?? 'النظام' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">لا توجد تحديثات تتبع</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
