@extends('layouts.app')
@section('content')
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="shop-checkout container">
      <h2 class="page-title">الشحن والدفع</h2>
      <div class="checkout-steps">
        <a href="{{ route('cart.index') }}" class="checkout-steps__item active">
          <span class="checkout-steps__item-number">01</span>
          <span class="checkout-steps__item-title">
            <span>حقيبة التسوق</span>
            <em>إدارة قائمة العناصر الخاصة بك</em>
          </span>
        </a>
        <a href="javascript:void(0)" class="checkout-steps__item active">
          <span class="checkout-steps__item-number">02</span>
          <span class="checkout-steps__item-title">
            <span>الشحن والدفع</span>
            <em>إتمام عملية الشراء</em>
          </span>
        </a>
        <a href="javascript:void(0)" class="checkout-steps__item">
          <span class="checkout-steps__item-number">03</span>
          <span class="checkout-steps__item-title">
            <span>التأكيد</span>
            <em>مراجعة وإرسال طلبك</em>
          </span>
        </a>
      </div>
      <!-- عرض رسائل الخطأ والنجاح -->
      @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          {{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <ul class="mb-0">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      <form name="checkout-form" action="{{ route('cart.place.an.order') }}" method="POST">
        @csrf
        <div class="checkout-form">
          <div class="billing-info__wrapper">
            <div class="row">
              <div class="col-6">
                <h4>تفاصيل الشحن</h4>
              </div>
              <div class="col-6">
              </div>
            </div>
            @if($address)
                <div class="row">
                    <div class="col-md-12">
                        <div class="my-account_address-list-item">
                            <div class="my-account_address-item_detail">
                                <p>{{$address->name}}</p>
                                <p>{{$address->address}}</p>
                                <p>{{$address->landmark}}</p>
                                <p>{{$address->city}},{{$address->state}},{{$address->country}}</p>
                                <p>{{$address->zip}}</p>
                                <br/>
                                <p>{{$address->phone}}</p>
                            </div>
                        </div>
                    </div>
                </div>



            @else
            <div class="row mt-5">
              <div class="col-md-6">
                <div class="form-floating my-3">
                  <input type="text" class="form-control" name="name" required="" value="{{ old('name')}}">
                  <label for="name">الاسم الكامل *</label>
                  @error('name')<span class="text-danger">{{ $message }}</span> @enderror
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-floating my-3">
                  <input type="text" class="form-control" name="phone" required="" value="{{ old('phone')}}">
                  <label for="phone">رقم الهاتف *</label>
                  @error('phone')<span class="text-danger">{{ $message }}</span> @enderror
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-floating my-3">
                  <input type="text" class="form-control" name="zip" required="" value="{{ old('zip')}}">
                  <label for="zip">الرمز البريدي *</label>
                  @error('zip')<span class="text-danger">{{ $message }}</span> @enderror
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-floating mt-3 mb-3">
                  <input type="text" class="form-control" name="state" required="" value="{{ old('state')}}">
                  <label for="state">المحافظة *</label>
                  @error('state')<span class="text-danger">{{ $message }}</span> @enderror
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-floating my-3">
                  <input type="text" class="form-control" name="city" required="" value="{{ old('city')}}">
                  <label for="city">المدينة *</label>
                  @error('city')<span class="text-danger">{{ $message }}</span> @enderror
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-floating my-3">
                  <input type="text" class="form-control" name="address" required="" value="{{ old('address')}}">
                  <label for="address">رقم المنزل، اسم المبنى *</label>
                  @error('address')<span class="text-danger">{{ $message }}</span> @enderror
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-floating my-3">
                  <input type="text" class="form-control" name="locality" required="" value="{{ old('locality')}}">
                  <label for="locality">اسم الشارع، المنطقة، الحي *</label>
                  @error('locality')<span class="text-danger">{{ $message }}</span> @enderror
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-floating my-3">
                  <input type="text" class="form-control" name="landmark" required="" value="{{ old('landmark')}}">
                  <label for="landmark">معلم مميز *</label>
                  @error('landmark')<span class="text-danger">{{ $message }}</span> @enderror
                </div>
              </div>
            </div>
            @endif
          </div>
          <div class="checkout__totals-wrapper">
            <div class="sticky-content">
              <div class="checkout__totals">
                <h3>طلبك</h3>
                <table class="checkout-cart-items">
                  <thead>
                    <tr>
                      <th>المنتج</th>
                      <th align="right">المجموع الفرعي</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach(Cart::instance('cart') as $item)
                    <tr>
                      <td>
                        {{ $item->name}} x {{ $item->qty  }}
                      </td>
                      <td align="right">
                        ${{$item->subtotal()}}
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
                @if(Session::has('discounts'))
                    <table class="checkout-totals">
                        <tbody>
                            <tr>
                              <th>المجموع الفرعي</th>
                              <td class="text-right">${{Cart::instance('cart')->subtotal()}}</td>
                            </tr>
                            <tr>
                              <th>خصم {{ Session::get('coupon')['code'] }}</th>
                              <td class="text-right">${{Session::get('discounts')['discount']}}</td>
                            </tr>
                            <tr>
                              <th>المجموع الفرعي بعد الخصم</th>
                              <td class="text-right">${{Session::get('discounts')['subtotal']}}</td>
                            </tr>
                            <tr>
                              <th>الشحن</th>
                              <td class="text-right">مجاني</td>
                            </tr>
                            <tr>
                              <th>ضريبة القيمة المضافة</th>
                              <td class="text-right">${{Session::get('discounts')['tax']}}</td>
                            </tr>
                            <tr>
                              <th>الإجمالي</th>
                              <td class="text-right">${{Session::get('discounts')['total']}}</td>
                            </tr>
                          </tbody>
                  </table>
                @else
                <table class="checkout-totals">
                  <tbody>
                    <tr>
                      <th>المجموع الفرعي</th>
                      <td class="text-right">${{Cart::instance('cart')->subtotal()}}</td>
                    </tr>
                    <tr>
                      <th>الشحن</th>
                      <td class="text-right">شحن مجاني</td>
                    </tr>
                    <tr>
                      <th>ضريبة القيمة المضافة</th>
                      <td class="text-right">${{Cart::instance('cart')->tax()}}</td>
                    </tr>
                    <tr>
                      <th>الإجمالي</th>
                      <td class="text-right">${{Cart::instance('cart')->total()}}</td>
                    </tr>
                  </tbody>
                </table>
                @endif
              </div>
              <div class="checkout__payment-methods">

                <div class="form-check">
                  <input class="form-check-input form-check-input_fill" type="radio" name="mode" id="mode1" value="card">
                  <label class="form-check-label" for="mode1">
                   بطاقة ائتمان أو خصم
                  </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input form-check-input_fill" type="radio" name="mode"
                      id="mode2" value="paypal">
                    <label class="form-check-label" for="mode2">
                      باي بال
                    </label>
                  </div>
                <div class="form-check">
                  <input class="form-check-input form-check-input_fill" type="radio" name="mode"
                    id="mode3" value="cod">
                  <label class="form-check-label" for="mode3">
                    الدفع عند الاستلام
                  </label>
                </div>
                @error('mode')<span class="text-danger">{{ $message }}</span> @enderror

                <div class="policy-text">
                  ستُستخدم بياناتك الشخصية لمعالجة طلبك ودعم تجربتك في هذا الموقع والأغراض الأخرى الموضحة في
                  <a href="terms.html" target="_blank">سياسة الخصوصية</a> الخاصة بنا.
                </div>
              </div>
              <button type="submit" class="btn btn-primary btn-checkout" onclick="return validatePaymentMethod()">تأكيد الطلب</button>
            </div>
          </div>
        </div>
      </form>
    </section>
  </main>

  <script>
    function validatePaymentMethod() {
      const paymentMethods = document.querySelectorAll('input[name="mode"]');
      let isSelected = false;

      paymentMethods.forEach(function(method) {
        if (method.checked) {
          isSelected = true;
        }
      });

      if (!isSelected) {
        alert('يرجى اختيار طريقة الدفع');
        return false;
      }

      return true;
    }
  </script>
@endsection
