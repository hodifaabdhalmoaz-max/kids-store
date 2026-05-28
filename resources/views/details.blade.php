@extends('layouts.app')
@section('content')
<style>
  .filled-heart {
    color: orange;
  }
</style>
<main class="pt-90">
  <div class="mb-md-1 pb-md-3"></div>
  <section class="product-single container">
    <div class="row">
      <div class="col-lg-7">
        <div class="product-single__media" data-media-type="vertical-thumbnail">
          <div class="product-single__image">
            <div class="swiper-container">
              <div class="swiper-wrapper">

                <div class="swiper-slide product-single__image-item">
                  <img loading="lazy" class="h-auto" src="{{asset('uploads/products')}}/{{$product->image}}" width="674" height="674" alt="" />
                  <a data-fancybox="gallery" href="{{asset('uploads/products')}}/{{$product->image}}" data-bs-toggle="tooltip" data-bs-placement="left" title="تكبير">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <use href="#icon_zoom" />
                    </svg>
                  </a>
                </div>

                @foreach(explode(',',$product->images) as $gimg)
                <div class="swiper-slide product-single__image-item">
                  <img loading="lazy" class="h-auto" src="{{asset('uploads/products')}}/{{$gimg}}" width="674" height="674" alt="" />
                  <a data-fancybox="gallery" href="{{asset('uploads/products')}}/{{$gimg}}" data-bs-toggle="tooltip" data-bs-placement="left" title="تكبير">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <use href="#icon_zoom" />
                    </svg>
                  </a>
                </div>
                @endforeach
              </div>
              <div class="swiper-button-prev"><svg width="7" height="11" viewBox="0 0 7 11"
                  xmlns="http://www.w3.org/2000/svg">
                  <use href="#icon_prev_sm" />
                </svg></div>
              <div class="swiper-button-next"><svg width="7" height="11" viewBox="0 0 7 11"
                  xmlns="http://www.w3.org/2000/svg">
                  <use href="#icon_next_sm" />
                </svg></div>
            </div>
          </div>
          <div class="product-single__thumbnail">
            <div class="swiper-container">
              <div class="swiper-wrapper">
                <div class="swiper-slide product-single__image-item"><img loading="lazy" class="h-auto" src="{{asset('uploads/products/thumbnails')}}/{{$product->image}}" width="104" height="104" alt="" /></div>
                @foreach(explode(',',$product->images) as $gimg)
                <div class="swiper-slide product-single__image-item"><img loading="lazy" class="h-auto" src="{{asset('uploads/products/thumbnails')}}/{{$gimg}}" width="104" height="104" alt="" /></div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="d-flex justify-content-between mb-4 pb-md-2">
          <div class="breadcrumb mb-0 d-none d-md-block flex-grow-1">
            <a href="#" class="menu-link menu-link_us-s text-uppercase fw-medium">الرئيسية</a>
            <span class="breadcrumb-separator menu-link fw-medium ps-1 pe-1">/</span>
            <a href="#" class="menu-link menu-link_us-s text-uppercase fw-medium">المتجر</a>
          </div><!-- /.breadcrumb -->

          <div
            class="product-single__prev-next d-flex align-items-center justify-content-between justify-content-md-end flex-grow-1">
            <a href="#" class="text-uppercase fw-medium"><svg width="10" height="10" viewBox="0 0 25 25"
                xmlns="http://www.w3.org/2000/svg">
                <use href="#icon_prev_md" />
              </svg><span class="menu-link menu-link_us-s">السابق</span></a>
            <a href="#" class="text-uppercase fw-medium"><span class="menu-link menu-link_us-s">التالي</span><svg
                width="10" height="10" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg">
                <use href="#icon_next_md" />
              </svg></a>
          </div><!-- /.shop-acs -->
        </div>
        <h1 class="product-single__name">{{$product->name}}</h1>
        <div class="product-single__rating">
          <x-star-rating :rating="$product->average_rating" :count="$product->review_count" />
        </div>
        <div class="product-single__price">
          <span class="current-price">
            @if($product->sale_price)
            <s>{{ format_price($product->regular_price) }}</s> {{ format_price($product->sale_price) }}
            @else
            {{ format_price($product->regular_price) }}
            @endif
          </span>
        </div>
        <div class="product-single__short-desc">
          <p>{{$product->short_description}}</p>
        </div>
        @if(Cart::instance('cart')->content()->where('id', $product->id)->count()>0)
        <a href="{{route('cart.index')}}" class="btn btn-warning mb-3">الذهاب للسلة</a>
        @else
        <form name="addtocart-form" method="post" action="{{route('cart.add')}}">
          @csrf
          <div class="product-single__addtocart">
            <div class="qty-control position-relative">
              <input type="number" name="quantity" value="1" min="1" class="qty-control__number text-center">
              <div class="qty-control__reduce">-</div>
              <div class="qty-control__increase">+</div>
            </div><!-- .qty-control -->
            <input type="hidden" name="id" value="{{$product->id}}" />
            <input type="hidden" name="name" value="{{$product->name}}" />
            <input type="hidden" name="price" value="{{$product->sale_price == '' ? $product->regular_price : $product->sale_price}}" />
            <button type="submit" class="btn btn-primary btn-addtocart" data-aside="cartDrawer">إضافة للسلة</button>
          </div>
        </form>
        @endif
        <div class="product-single__addtolinks">
          @if(Cart::instance('wishlist')->content()->where('id',$product->id)->count()>0)
          <form method="POST" action="{{ route('wishlist.item.remove',['rowId'=>Cart::instance('wishlist')->content()->where('id',$product->id)->first()->rowId])}}" id="frm-remove-item">
            @csrf
            @method('DELETE')
            <a href="javascript:void(0)" class="menu-link menu-link_us-s add-to-wishlist filled-heart" onclick="document.getElementById('frm-remove-item').submit();"><svg width="16" height="16" viewBox="0 0 20 20"
                fill="none" xmlns="http://www.w3.org/2000/svg">
                <use href="#icon_heart" />
              </svg><span>إزالة من المفضلة</span></a>
          </form>
          @else
          <form method="POST" action="{{route('wishlist.add')}}" id="wishlist-form">
            @csrf
            <input type="hidden" name="id" value="{{ $product->id }}" />
            <input type="hidden" name="name" value="{{ $product->name }}" />
            <input type="hidden" name="price" value="{{ $product->sale_price=='' ? $product->regular_price : $product->sale_price }}" />
            <input type="hidden" name="quantity" value="1" />
            <a href="javascript:void(0)" class="menu-link menu-link_us-s add-to-wishlist" onclick="document.getElementById('wishlist-form').submit();"><svg width="16" height="16" viewBox="0 0 20 20"
                fill="none" xmlns="http://www.w3.org/2000/svg">
                <use href="#icon_heart" />
              </svg><span>إضافة للمفضلة</span></a>
          </form>
          @endif

          <button type="button" class="menu-link menu-link_us-s to-share border-0 bg-transparent d-flex align-items-center" onclick="shareProduct()">
            <i class="bi bi-share me-2" style="font-size: 16px;"></i>
            <span>مشاركة</span>
          </button>
        </div>
        <div class="product-single__meta-info">
          <div class="meta-item">
            <label>SKU:</label>
            <span>{{$product->SKU}}</span>
          </div>
          <div class="meta-item">
            <label>الفئات:</label>
            <span>{{$product->category->name}}</span>
          </div>
          <div class="meta-item">
            <label>الكمية في المخزون:</label>
            @if($product->quantity > 0)
              <span class="text-success fw-bold">{{ $product->quantity }} قطعة</span>
            @else
              <span class="text-danger fw-bold">غير متوفر</span>
            @endif
          </div>
        </div>
      </div>
    </div>
    <div class="product-single__details-tab">
      <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
          <a class="nav-link nav-link_underscore active" id="tab-description-tab" data-bs-toggle="tab"
            href="#tab-description" role="tab" aria-controls="tab-description" aria-selected="true">الوصف</a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link nav-link_underscore" id="tab-additional-info-tab" data-bs-toggle="tab"
            href="#tab-additional-info" role="tab" aria-controls="tab-additional-info"
            aria-selected="false">معلومات إضافية</a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link nav-link_underscore" id="tab-reviews-tab" data-bs-toggle="tab" href="#tab-reviews"
            role="tab" aria-controls="tab-reviews" aria-selected="false">التقييمات ({{ $product->reviews->count() }})</a>
        </li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane fade show active" id="tab-description" role="tabpanel"
          aria-labelledby="tab-description-tab">
          <div class="product-single__description">
            {{$product->description}}
          </div>
        </div>
        <div class="tab-pane fade" id="tab-additional-info" role="tabpanel" aria-labelledby="tab-additional-info-tab">
          <div class="product-single__addtional-info">
            @if($product->weight)
            <div class="item">
              <label class="h6">الوزن</label>
              <span>{{$product->weight}}</span>
            </div>
            @endif
            @if($product->dimensions)
            <div class="item">
              <label class="h6">الأبعاد</label>
              <span>{{$product->dimensions}}</span>
            </div>
            @endif
            @if($product->size)
            <div class="item">
              <label class="h6">المقاس</label>
              <span>{{$product->size}}</span>
            </div>
            @endif
            @if($product->color)
            <div class="item">
              <label class="h6">اللون</label>
              <span>{{$product->color}}</span>
            </div>
            @endif
          </div>
        </div>
        <div class="tab-pane fade" id="tab-reviews" role="tabpanel" aria-labelledby="tab-reviews-tab">
          <h2 class="product-single__reviews-title">التقييمات</h2>

          @if(session('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
          </div>
          @endif
          @if(session('error'))
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
          </div>
          @endif

          @if($product->reviews->count() > 0)
          {{-- ملخص التقييمات --}}
          <div class="reviews-summary mb-4 p-3" style="background: #f8f9fa; border-radius: 8px;">
            <div class="d-flex align-items-center gap-3">
              <div class="text-center">
                <div style="font-size: 2rem; font-weight: 700; color: #333;">{{ number_format($product->average_rating, 1) }}</div>
                <x-star-rating :rating="$product->average_rating" :count="$product->reviews->count()" />
              </div>
            </div>
          </div>

          <div class="product-single__reviews-list">
            @foreach($product->reviews as $review)
            <div class="product-single__reviews-item">
              <div class="customer-avatar">
                @if(optional($review->user)->profile_photo)
                <div style="width: 50px; height: 50px; border-radius: 50%; overflow: hidden;">
                  <img src="{{ asset('storage/profile_photos/' . $review->user->profile_photo) }}" alt="{{ $review->user->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                @else
                <div style="width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, #d4a853, #f0d78c); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 1.2rem;">
                  {{ mb_substr(optional($review->user)->name ?? 'م', 0, 1, 'UTF-8') }}
                </div>
                @endif
              </div>
              <div class="customer-review">
                <div class="customer-name">
                  <h6>{{ optional($review->user)->name ?? 'مستخدم' }}</h6>
                  <div class="reviews-group d-flex">
                    @for($i = 1; $i <= 5; $i++)
                      <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg" style="{{ $i > $review->rating ? 'opacity: 0.2' : '' }}">
                      <use href="#icon_star" />
                      </svg>
                      @endfor
                  </div>
                </div>
                <div class="review-date">{{ $review->created_at->translatedFormat('d M Y') }}</div>
                <div class="review-text">
                  <p>{{ $review->comment }}</p>
                </div>
              </div>
            </div>
            @endforeach
          </div>
          @else
          <div class="text-center py-4" style="color: #999;">
            <p>لا توجد تقييمات لهذا المنتج بعد. كن أول من يقيّم!</p>
          </div>
          @endif

          {{-- نموذج إضافة تقييم --}}
          <div class="product-single__review-form">
            @auth
            @php
            $existingReview = $product->reviews->where('user_id', auth()->id())->first();
            @endphp
            @if($existingReview)
            <div class="alert alert-info">
              <strong>لقد قمت بتقييم هذا المنتج مسبقاً.</strong> شكراً لمشاركتك!
            </div>
            @else
            <form method="POST" action="{{ route('review.store', $product->id) }}">
              @csrf
              <h5>أضف تقييمك لـ "{{ $product->name }}"</h5>
              <p>لن يتم نشر عنوان بريدك الإلكتروني. الحقول المطلوبة مشار إليها بـ *</p>
              <div class="select-star-rating mb-3">
                <label>تقييمك *</label>
                <div class="star-rating-input d-flex gap-1 mt-1" id="starRatingInput">
                  @for($i = 1; $i <= 5; $i++)
                    <svg class="star-rating__star-icon" data-rating="{{ $i }}" width="24" height="24" fill="#ccc" viewBox="0 0 12 12"
                    xmlns="http://www.w3.org/2000/svg" style="cursor: pointer; transition: fill 0.2s ease;">
                    <path
                      d="M11.1429 5.04687C11.1429 4.84598 10.9286 4.76562 10.7679 4.73884L7.40625 4.25L5.89955 1.20312C5.83929 1.07589 5.72545 0.928571 5.57143 0.928571C5.41741 0.928571 5.30357 1.07589 5.2433 1.20312L3.73661 4.25L0.375 4.73884C0.207589 4.76562 0 4.84598 0 5.04687C0 5.16741 0.0870536 5.28125 0.167411 5.3683L2.60491 7.73884L2.02902 11.0871C2.02232 11.1339 2.01563 11.1741 2.01563 11.221C2.01563 11.3951 2.10268 11.5558 2.29688 11.5558C2.39063 11.5558 2.47768 11.5223 2.56473 11.4754L5.57143 9.89509L8.57813 11.4754C8.65848 11.5223 8.75223 11.5558 8.84598 11.5558C9.04018 11.5558 9.12054 11.3951 9.12054 11.221C9.12054 11.1741 9.12054 11.1339 9.11384 11.0871L8.53795 7.73884L10.9688 5.3683C11.0558 5.28125 11.1429 5.16741 11.1429 5.04687Z" />
                    </svg>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="form-input-rating" value="" required />
                @error('rating')
                <div class="text-danger mt-1" style="font-size: 0.875rem;">{{ $message }}</div>
                @enderror
              </div>
              <div class="mb-4">
                <textarea name="comment" id="form-input-review" class="form-control form-control_gray" placeholder="اكتب تقييمك هنا..."
                  cols="30" rows="5" required>{{ old('comment') }}</textarea>
                @error('comment')
                <div class="text-danger mt-1" style="font-size: 0.875rem;">{{ $message }}</div>
                @enderror
              </div>
              <div class="form-action">
                <button type="submit" class="btn btn-primary">إرسال التقييم</button>
              </div>
            </form>
            @endif
            @else
            <div class="text-center py-3">
              <p>يجب عليك <a href="{{ route('login') }}" class="fw-bold" style="color: #d4a853;">تسجيل الدخول</a> لإضافة تقييم.</p>
            </div>
            @endauth
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="products-carousel container">
    <h2 class="h3 text-uppercase mb-4 pb-xl-2 mb-xl-4">المنتجات <strong>ذات الصلة</strong></h2>

    <div id="related_products" class="position-relative">
      <div class="swiper-container js-swiper-slider" data-settings='{
            "autoplay": false,
            "slidesPerView": 4,
            "slidesPerGroup": 4,
            "effect": "none",
            "loop": true,
            "pagination": {
              "el": "#related_products .products-pagination",
              "type": "bullets",
              "clickable": true
            },
            "navigation": {
              "nextEl": "#related_products .products-carousel__next",
              "prevEl": "#related_products .products-carousel__prev"
            },
            "breakpoints": {
              "320": {
                "slidesPerView": 2,
                "slidesPerGroup": 2,
                "spaceBetween": 14
              },
              "768": {
                "slidesPerView": 3,
                "slidesPerGroup": 3,
                "spaceBetween": 24
              },
              "992": {
                "slidesPerView": 4,
                "slidesPerGroup": 4,
                "spaceBetween": 30
              }
            }
          }'>
        <div class="swiper-wrapper">
          @foreach($rproducts as $rproduct)
          <div class="swiper-slide product-card">
            <div class="pc__img-wrapper">
              <a href="{{route('shop.product.details',['product_slug'=>$rproduct->slug])}}">
                <img loading="lazy" src="{{asset('uploads/products')}}/{{$rproduct->image}}" width="330" height="400" alt="{{$rproduct->name}}" class="pc__img">
                @foreach(explode(",",$rproduct->images) as $gimg)
                <img loading="lazy" src="{{asset('uploads/products')}}/{{$gimg}}" width="330" height="400" alt="{{$rproduct->name}}" class="pc__img pc__img-second">
                @endforeach
              </a>
              @if(Cart::instance('cart')->content()->where('id', $rproduct->id)->count()>0)
              <a href="{{route('cart.index')}}" class="cart-icon-btn" title="الذهاب للسلة"><i class="bi bi-cart-check fs-5"></i></a>
              @else
              <form name="addtocart-form" method="post" action="{{route('cart.add')}}">
                @csrf
                <input type="hidden" name="id" value="{{$rproduct->id}}" />
                <input type="hidden" name="quantity" value="1" />
                <input type="hidden" name="name" value="{{$rproduct->name}}" />
                <input type="hidden" name="price" value="{{$rproduct->sale_price == '' ? $rproduct->regular_price : $rproduct->sale_price}}" />
                <button type="submit" class="cart-icon-btn border-0" data-aside="cartDrawer" title="إضافة للسلة"><i class="bi bi-cart-plus fs-5"></i></button>
              </form>
              @endif
            </div>

            <div class="pc__info position-relative">
              <h6 class="pc__title"><a href="{{route('shop.product.details',['product_slug'=>$rproduct->slug])}}">{{$rproduct->card_title}}</a></h6>
              <div class="product-card__price d-flex">
                <span class="money price">
                  @if($rproduct->sale_price)
                  <s>{{ format_price($rproduct->regular_price) }}</s> {{ format_price($rproduct->sale_price) }}
                  @else
                  {{ format_price($rproduct->regular_price) }}
                  @endif
                </span>
              </div>
              <x-star-rating :rating="$rproduct->active_reviews_avg" :count="$rproduct->active_reviews_count" />
              <div class="product-info-badges">
                <div class="delivery-badge">
                  <i class="bi bi-clock"></i>
                  <span>يصلك في <b>1-7</b> أيام</span>
                </div>
                <div class="shipping-badge">
                  <i class="bi bi-truck"></i>
                  <span>شحن مجاني</span>
                </div>
              </div>

              <button class="pc__btn-wl position-absolute top-0 end-0 bg-transparent border-0 js-add-wishlist"
                title="إضافة للمفضلة">
                <svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <use href="#icon_heart" />
                </svg>
              </button>
            </div>
          </div>
          @endforeach

        </div><!-- /.swiper-wrapper -->
      </div><!-- /.swiper-container js-swiper-slider -->

      <div class="products-carousel__prev position-absolute top-50 d-flex align-items-center justify-content-center">
        <svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg">
          <use href="#icon_prev_md" />
        </svg>
      </div><!-- /.products-carousel__prev -->
      <div class="products-carousel__next position-absolute top-50 d-flex align-items-center justify-content-center">
        <svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg">
          <use href="#icon_next_md" />
        </svg>
      </div><!-- /.products-carousel__next -->

      <div class="products-pagination mt-4 mb-5 d-flex align-items-center justify-content-center"></div>
      <!-- /.products-pagination -->
    </div><!-- /.position-relative -->

  </section><!-- /.products-carousel container -->
</main>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const starInput = document.getElementById('form-input-rating');
    const stars = document.querySelectorAll('#starRatingInput .star-rating__star-icon');

    if (stars.length > 0) {
      stars.forEach(star => {
        // Hover effect
        star.addEventListener('mouseenter', function() {
          const rating = this.getAttribute('data-rating');
          updateStars(rating);
        });

        // Click effect
        star.addEventListener('click', function() {
          const rating = this.getAttribute('data-rating');
          starInput.value = rating;
          updateStars(rating);

          // Remove error message if exists when user selects a rating
          const errorContainer = this.closest('.select-star-rating').querySelector('.text-danger');
          if (errorContainer) {
            errorContainer.style.display = 'none';
          }
        });
      });

      // Reset stars on mouseleave from the container, unless a rating is selected
      const starContainer = document.getElementById('starRatingInput');
      starContainer.addEventListener('mouseleave', function() {
        const currentRating = starInput.value || 0;
        updateStars(currentRating);
      });
    }

    function updateStars(rating) {
      stars.forEach(s => {
        if (parseInt(s.getAttribute('data-rating')) <= parseInt(rating)) {
          s.setAttribute('fill', '#f5b223'); // Custom theme gold color for filled stars
        } else {
          s.setAttribute('fill', '#ccc'); // Gray color for empty stars
        }
      });
    }

    // If there's an old value (validation failed for comment), restore stars
    if (starInput && starInput.value) {
      updateStars(starInput.value);
    }
  });

  // Share product using Web Share API or clipboard fallback
  function shareProduct() {
    const shareData = {
      title: '{{ $product->name }}',
      text: '{{ Str::limit($product->short_description, 100) }}',
      url: window.location.href
    };
    if (navigator.share) {
      navigator.share(shareData).catch(() => {});
    } else {
      navigator.clipboard.writeText(window.location.href).then(() => {
        alert('تم نسخ رابط المنتج!');
      });
    }
  }
</script>
@endpush
@endsection