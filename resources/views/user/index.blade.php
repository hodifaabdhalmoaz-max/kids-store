@extends('layouts.app')
@section('content')
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="my-account container">
      <h2 class="page-title">حسابي</h2>
      <div class="row">
        <div class="col-lg-3">
            @include('user.account-nav')
        </div>
        <div class="col-lg-9">
          <div class="page-content my-account__dashboard">
            <p>مرحباً <strong>{{Auth::user()->name}}</strong></p>
            <p>من لوحة تحكم حسابك يمكنك عرض <a class="unerline-link" href="account_orders.html">طلباتك الأخيرة</a>، وإدارة <a class="unerline-link" href="account_edit_address.html">عناوين الشحن</a>، و<a class="unerline-link" href="account_edit.html">تعديل كلمة المرور وتفاصيل حسابك.</a></p>
          </div>
        </div>
      </div>
    </section>
  </main>
@endsection
