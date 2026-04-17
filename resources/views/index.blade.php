@extends('layouts.app')
@section('content')
<main>

  <section class="swiper-container js-swiper-slider swiper-number-pagination slideshow" data-settings='{
        "autoplay": {
          "delay": 5000
        },
        "slidesPerView": 1,
        "effect": "fade",
        "loop": true
      }'>
    <div class="swiper-wrapper">
      <div class="swiper-slide">
        <div class="overflow-hidden position-relative h-100">
          <div class="slideshow-character position-absolute bottom-0 pos_left-center">
            <img loading="lazy" src="{{ asset('assets/images/home/demo3/slideshow-character1.png') }}" width="542" height="733"
              alt="ملابس أطفال عصرية"
              class="slideshow-character__img animate animate_fade animate_btt animate_delay-9 w-auto h-auto" />
            <div class="character_markup type2">
              <p
                class="text-uppercase font-sofia mark-grey-color animate animate_fade animate_btt animate_delay-10 mb-0">
                So Cuts</p>
            </div>
          </div>
          <div class="slideshow-text container position-absolute start-50 top-50 translate-middle">
            <h6 class="text_inline_dash text-uppercase fs-base fw-medium animate animate_fade animate_btt animate_delay-3">
              وصل حديثاً</h6>
            <h2 class="h1 fw-normal mb-0 animate animate_fade animate_btt animate_delay-5">طفل سعيد</h2>
            <h2 class="h1 fw-bold animate animate_fade animate_btt animate_delay-7">مع متجرنا</h2>
            <a href="#"
              class="btn-link btn-link_lg default-underline fw-medium animate animate_fade animate_btt animate_delay-7">تسوق
              الآن</a>
          </div>
        </div>
      </div>

      <div class="swiper-slide">
        <div class="overflow-hidden position-relative h-100">
          <div class="slideshow-character position-absolute bottom-0 pos_left-center">
            <img loading="lazy" src="{{ asset('assets/images/slideshow-character1.png') }}" width="400" height="733"
              alt="مجموعة أطفال شتوية"
              class="slideshow-character__img animate animate_fade animate_btt animate_delay-9 w-auto h-auto" />
            <div class="character_markup">
              <p class="text-uppercase font-sofia fw-bold animate animate_fade animate_rtl animate_delay-10">للشتاء
              </p>
            </div>
          </div>
          <div class="slideshow-text container position-absolute start-50 top-50 translate-middle">
            <h6 class="text_inline_dash text-uppercase fs-base fw-medium animate animate_fade animate_btt animate_delay-3">
              وصل حديثاً</h6>
            <h2 class="h1 fw-normal mb-0 animate animate_fade animate_btt animate_delay-5">عالم الأطفال</h2>
            <h2 class="h1 fw-bold animate animate_fade animate_btt animate_delay-7">المميز</h2>
            <a href="#"
              class="btn-link btn-link_lg default-underline fw-medium animate animate_fade animate_btt animate_delay-7">تسوق
              الآن</a>
          </div>
        </div>
      </div>

      <div class="swiper-slide">
        <div class="overflow-hidden position-relative h-100">
          <div class="slideshow-character position-absolute bottom-0 pos_left-center">
            <img loading="lazy" src="{{ asset('assets/images/slideshow-character2.png') }}" width="400" height="690"
              alt="منتجات أطفال ممتعة"
              class="slideshow-character__img animate animate_fade animate_rtl animate_delay-10 w-auto h-auto" />
          </div>
          <div class="slideshow-text container position-absolute start-50 top-50 translate-middle">
            <h6 class="text_inline_dash text-uppercase fs-base fw-medium animate animate_fade animate_btt animate_delay-3">
              وصل حديثاً</h6>
            <h2 class="h1 fw-normal mb-0 animate animate_fade animate_btt animate_delay-5">أشياء ممتعة</h2>
            <h2 class="h1 fw-bold animate animate_fade animate_btt animate_delay-7">لطفلك</h2>
            <a href="#"
              class="btn-link btn-link_lg default-underline fw-medium animate animate_fade animate_btt animate_delay-7">تسوق
              الآن</a>
          </div>
        </div>
      </div>
    </div>

    <div class="container">
      <div
        class="slideshow-pagination slideshow-number-pagination d-flex align-items-center position-absolute bottom-0 mb-5">
      </div>
    </div>
  </section>
  <div class="container mw-1620 bg-white border-radius-10">
    <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>
    <section class="category-carousel container">
      <h2 class="section-title text-center mb-5">قد يعجبك أيضاً</h2>

      <div class="position-relative">
        <div class="swiper-container js-swiper-slider" data-settings='{
              "autoplay": {
                "delay": 5000
              },
              "slidesPerView": 8,
              "slidesPerGroup": 1,
              "effect": "none",
              "loop": true,
              "navigation": {
                "nextEl": ".products-carousel__next-1",
                "prevEl": ".products-carousel__prev-1"
              },
              "breakpoints": {
                "320": {
                  "slidesPerView": 2,
                  "slidesPerGroup": 2,
                  "spaceBetween": 15
                },
                "768": {
                  "slidesPerView": 4,
                  "slidesPerGroup": 4,
                  "spaceBetween": 30
                },
                "992": {
                  "slidesPerView": 6,
                  "slidesPerGroup": 1,
                  "spaceBetween": 45,
                  "pagination": false
                },
                "1200": {
                  "slidesPerView": 8,
                  "slidesPerGroup": 1,
                  "spaceBetween": 60,
                  "pagination": false
                }
              }
            }'>
          <div class="swiper-wrapper">
            @foreach($categories as $category)
            <div class="swiper-slide">
              <img loading="lazy" class="w-100 h-auto mb-3"
                src="{{ asset('uploads/categories/' . $category->image) }}?v={{ time() }}"
                width="124" height="124"
                alt="{{ $category->name }}"
                onerror="this.src='{{ asset('assets/images/home/demo3/category_1.png') }}'" />
              <div class="text-center">
                <a href="{{ route('shop.category', $category->slug) }}" class="menu-link fw-medium">{{ $category->name }}</a>
              </div>
            </div>
            @endforeach
          </div><!-- /.swiper-wrapper -->
        </div><!-- /.swiper-container js-swiper-slider -->

        <div
          class="products-carousel__prev products-carousel__prev-1 position-absolute top-50 d-flex align-items-center justify-content-center">
          <svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg">
            <use href="#icon_prev_md" />
          </svg>
        </div><!-- /.products-carousel__prev -->
        <div
          class="products-carousel__next products-carousel__next-1 position-absolute top-50 d-flex align-items-center justify-content-center">
          <svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg">
            <use href="#icon_next_md" />
          </svg>
        </div><!-- /.products-carousel__next -->
      </div><!-- /.position-relative -->
    </section>

    <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>

    <section class="hot-deals container">
      <h2 class="section-title text-center mb-5">العروض الساخنة</h2>
      <div class="row">
        <div
          class="col-md-6 col-lg-4 col-xl-20per d-flex align-items-center flex-column justify-content-center py-4 align-items-md-start">
          <h2>تخفيضات الشتاء</h2>
          <h2 class="fw-bold">خصم يصل إلى 50%</h2>

          <div class="position-relative d-flex align-items-center text-center pt-xxl-4 js-countdown mb-3"
            data-date="18-3-2024" data-time="06:50">
            <div class="day countdown-unit">
              <span class="countdown-num d-block"></span>
              <span class="countdown-word text-uppercase text-secondary">أيام</span>
            </div>

            <div class="hour countdown-unit">
              <span class="countdown-num d-block"></span>
              <span class="countdown-word text-uppercase text-secondary">ساعات</span>
            </div>

            <div class="min countdown-unit">
              <span class="countdown-num d-block"></span>
              <span class="countdown-word text-uppercase text-secondary">دقائق</span>
            </div>

            <div class="sec countdown-unit">
              <span class="countdown-num d-block"></span>
              <span class="countdown-word text-uppercase text-secondary">ثواني</span>
            </div>
          </div>

          <a href="#" class="btn-link default-underline text-uppercase fw-medium mt-3">عرض الكل</a>
        </div>
        <div class="col-md-6 col-lg-8 col-xl-80per">
          <div class="position-relative">
            <div class="swiper-container js-swiper-slider" data-settings='{
                  "autoplay": {
                    "delay": 5000
                  },
                  "slidesPerView": 4,
                  "slidesPerGroup": 4,
                  "effect": "none",
                  "loop": false,
                  "breakpoints": {
                    "320": {
                      "slidesPerView": 2,
                      "slidesPerGroup": 2,
                      "spaceBetween": 14
                    },
                    "768": {
                      "slidesPerView": 2,
                      "slidesPerGroup": 3,
                      "spaceBetween": 24
                    },
                    "992": {
                      "slidesPerView": 3,
                      "slidesPerGroup": 1,
                      "spaceBetween": 30,
                      "pagination": false
                    },
                    "1200": {
                      "slidesPerView": 4,
                      "slidesPerGroup": 1,
                      "spaceBetween": 30,
                      "pagination": false
                    }
                  }
                }'>
              <div class="swiper-wrapper">
                <div class="swiper-slide product-card product-card_style3">
                  <div class="pc__img-wrapper">
                    <a href="details.html">
                      <img loading="lazy" src="{{ asset('assets/images/home/demo3/product-0-1.jpg') }}" width="258" height="313"
                        alt="طقم ملابس أطفال قطني" class="pc__img">
                      <img loading="lazy" src="{{ asset('assets/images/home/demo3/product-0-2.jpg') }}" width="258" height="313"
                        alt="طقم ملابس أطفال قطني" class="pc__img pc__img-second">
                    </a>
                  </div>

                  <div class="pc__info position-relative">
                    <h6 class="pc__title"><a href="details.html">طقم ملابس أطفال قطني</a></h6>
                    <div class="product-card__price d-flex">
                      <span class="money price text-secondary">$29</span>
                    </div>

                    <div
                      class="anim_appear-bottom position-absolute bottom-0 start-0 d-none d-sm-flex align-items-center bg-body">
                      <button class="btn-link btn-link_lg me-4 text-uppercase fw-medium js-add-cart js-open-aside"
                        data-aside="cartDrawer" title="إضافة للسلة">إضافة للسلة</button>
                      <button class="btn-link btn-link_lg me-4 text-uppercase fw-medium js-quick-view"
                        data-bs-toggle="modal" data-bs-target="#quickView" title="عرض سريع">
                        <span class="d-none d-xxl-block">عرض سريع</span>
                        <span class="d-block d-xxl-none"><svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <use href="#icon_view" />
                          </svg></span>
                      </button>
                      <button class="pc__btn-wl bg-transparent border-0 js-add-wishlist" title="إضافة للمفضلة">
                        <svg width="16" height="16" viewBox="0 0 20 20" fill="none"
                          xmlns="http://www.w3.org/2000/svg">
                          <use href="#icon_heart" />
                        </svg>
                      </button>
                    </div>
                  </div>
                </div>
                <div class="swiper-slide product-card product-card_style3">
                  <div class="pc__img-wrapper">
                    <a href="details.html">
                      <img loading="lazy" src="{{ asset('assets/images/home/demo3/product-1-1.jpg') }}" width="258" height="313"
                        alt="حذاء رياضي للأطفال" class="pc__img">
                      <img loading="lazy" src="{{ asset('assets/images/home/demo3/product-1-2.jpg') }}" width="258" height="313"
                        alt="حذاء رياضي للأطفال" class="pc__img pc__img-second">
                    </a>
                  </div>

                  <div class="pc__info position-relative">
                    <h6 class="pc__title"><a href="details.html">حذاء رياضي للأطفال</a></h6>
                    <div class="product-card__price d-flex">
                      <span class="money price text-secondary">$62</span>
                    </div>

                    <div
                      class="anim_appear-bottom position-absolute bottom-0 start-0 d-none d-sm-flex align-items-center bg-body">
                      <button class="btn-link btn-link_lg me-4 text-uppercase fw-medium js-add-cart js-open-aside"
                        data-aside="cartDrawer" title="إضافة للسلة">إضافة للسلة</button>
                      <button class="btn-link btn-link_lg me-4 text-uppercase fw-medium js-quick-view"
                        data-bs-toggle="modal" data-bs-target="#quickView" title="عرض سريع">
                        <span class="d-none d-xxl-block">عرض سريع</span>
                        <span class="d-block d-xxl-none"><svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <use href="#icon_view" />
                          </svg></span>
                      </button>
                      <button class="pc__btn-wl bg-transparent border-0 js-add-wishlist" title="إضافة للمفضلة">
                        <svg width="16" height="16" viewBox="0 0 20 20" fill="none"
                          xmlns="http://www.w3.org/2000/svg">
                          <use href="#icon_heart" />
                        </svg>
                      </button>
                    </div>
                  </div>
                </div>
                <div class="swiper-slide product-card product-card_style3">
                  <div class="pc__img-wrapper">
                    <a href="details.html">
                      <img loading="lazy" src="{{ asset('assets/images/home/demo3/product-2-1.jpg') }}" width="258" height="313"
                        alt="لعبة تعليمية ملونة" class="pc__img">
                      <img loading="lazy" src="{{ asset('assets/images/home/demo3/product-2-2.jpg') }}" width="258" height="313"
                        alt="لعبة تعليمية ملونة" class="pc__img pc__img-second">
                    </a>
                  </div>

                  <div class="pc__info position-relative">
                    <h6 class="pc__title"><a href="details.html">لعبة تعليمية ملونة</a></h6>
                    <div class="product-card__price d-flex">
                      <span class="money price text-secondary">$62</span>
                    </div>

                    <div
                      class="anim_appear-bottom position-absolute bottom-0 start-0 d-none d-sm-flex align-items-center bg-body">
                      <button class="btn-link btn-link_lg me-4 text-uppercase fw-medium js-add-cart js-open-aside"
                        data-aside="cartDrawer" title="إضافة للسلة">إضافة للسلة</button>
                      <button class="btn-link btn-link_lg me-4 text-uppercase fw-medium js-quick-view"
                        data-bs-toggle="modal" data-bs-target="#quickView" title="عرض سريع">
                        <span class="d-none d-xxl-block">عرض سريع</span>
                        <span class="d-block d-xxl-none"><svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <use href="#icon_view" />
                          </svg></span>
                      </button>
                      <button class="pc__btn-wl bg-transparent border-0 js-add-wishlist" title="إضافة للمفضلة">
                        <svg width="16" height="16" viewBox="0 0 20 20" fill="none"
                          xmlns="http://www.w3.org/2000/svg">
                          <use href="#icon_heart" />
                        </svg>
                      </button>
                    </div>
                  </div>
                </div>
                <div class="swiper-slide product-card product-card_style3">
                  <div class="pc__img-wrapper">
                    <a href="details.html">
                      <img loading="lazy" src="{{ asset('assets/images/home/demo3/product-3-1.jpg') }}" width="258" height="313"
                        alt="جاكيت شتوي للأطفال" class="pc__img">
                      <img loading="lazy" src="{{ asset('assets/images/home/demo3/product-3-2.jpg') }}" width="258" height="313"
                        alt="جاكيت شتوي للأطفال" class="pc__img pc__img-second">
                    </a>
                  </div>

                  <div class="pc__info position-relative">
                    <h6 class="pc__title"><a href="details.html">جاكيت شتوي للأطفال</a></h6>
                    <div class="product-card__price d-flex align-items-center">
                      <span class="money price-old">$129</span>
                      <span class="money price text-secondary">$99</span>
                    </div>

                    <div
                      class="anim_appear-bottom position-absolute bottom-0 start-0 d-none d-sm-flex align-items-center bg-body">
                      <button class="btn-link btn-link_lg me-4 text-uppercase fw-medium js-add-cart js-open-aside"
                        data-aside="cartDrawer" title="إضافة للسلة">إضافة للسلة</button>
                      <button class="btn-link btn-link_lg me-4 text-uppercase fw-medium js-quick-view"
                        data-bs-toggle="modal" data-bs-target="#quickView" title="عرض سريع">
                        <span class="d-none d-xxl-block">عرض سريع</span>
                        <span class="d-block d-xxl-none"><svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <use href="#icon_view" />
                          </svg></span>
                      </button>
                      <button class="pc__btn-wl bg-transparent border-0 js-add-wishlist" title="إضافة للمفضلة">
                        <svg width="16" height="16" viewBox="0 0 20 20" fill="none"
                          xmlns="http://www.w3.org/2000/svg">
                          <use href="#icon_heart" />
                        </svg>
                      </button>
                    </div>
                  </div>
                </div>
                <div class="swiper-slide product-card product-card_style3">
                  <div class="pc__img-wrapper">
                    <a href="details.html">
                      <img loading="lazy" src="{{ asset('assets/images/home/demo3/product-0-1.jpg') }}" width="258" height="313"
                        alt="Cropped Faux leather Jacket" class="pc__img">
                      <img loading="lazy" src="{{ asset('assets/images/home/demo3/product-0-2.jpg') }}" width="258" height="313"
                        alt="Cropped Faux leather Jacket" class="pc__img pc__img-second">
                    </a>
                  </div>

                  <div class="pc__info position-relative">
                    <h6 class="pc__title"><a href="details.html">حقيبة مدرسية ملونة</a></h6>
                    <div class="product-card__price d-flex">
                      <span class="money price text-secondary">$29</span>
                    </div>

                    <div
                      class="anim_appear-bottom position-absolute bottom-0 start-0 d-none d-sm-flex align-items-center bg-body">
                      <button class="btn-link btn-link_lg me-4 text-uppercase fw-medium js-add-cart js-open-aside"
                        data-aside="cartDrawer" title="إضافة للسلة">إضافة للسلة</button>
                      <button class="btn-link btn-link_lg me-4 text-uppercase fw-medium js-quick-view"
                        data-bs-toggle="modal" data-bs-target="#quickView" title="عرض سريع">
                        <span class="d-none d-xxl-block">عرض سريع</span>
                        <span class="d-block d-xxl-none"><svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <use href="#icon_view" />
                          </svg></span>
                      </button>
                      <button class="pc__btn-wl bg-transparent border-0 js-add-wishlist" title="إضافة للمفضلة">
                        <svg width="16" height="16" viewBox="0 0 20 20" fill="none"
                          xmlns="http://www.w3.org/2000/svg">
                          <use href="#icon_heart" />
                        </svg>
                      </button>
                    </div>
                  </div>
                </div>
                <div class="swiper-slide product-card product-card_style3">
                  <div class="pc__img-wrapper">
                    <a href="details.html">
                      <img loading="lazy" src="{{ asset('assets/images/home/demo3/product-1-1.jpg') }}" width="258" height="313"
                        alt="Cropped Faux leather Jacket" class="pc__img">
                      <img loading="lazy" src="{{ asset('assets/images/home/demo3/product-1-2.jpg') }}" width="258" height="313"
                        alt="Cropped Faux leather Jacket" class="pc__img pc__img-second">
                    </a>
                  </div>

                  <div class="pc__info position-relative">
                    <h6 class="pc__title"><a href="details.html">بيجامة أطفال ناعمة</a></h6>
                    <div class="product-card__price d-flex">
                      <span class="money price text-secondary">$62</span>
                    </div>

                    <div
                      class="anim_appear-bottom position-absolute bottom-0 start-0 d-none d-sm-flex align-items-center bg-body">
                      <button class="btn-link btn-link_lg me-4 text-uppercase fw-medium js-add-cart js-open-aside"
                        data-aside="cartDrawer" title="إضافة للسلة">إضافة للسلة</button>
                      <button class="btn-link btn-link_lg me-4 text-uppercase fw-medium js-quick-view"
                        data-bs-toggle="modal" data-bs-target="#quickView" title="عرض سريع">
                        <span class="d-none d-xxl-block">عرض سريع</span>
                        <span class="d-block d-xxl-none"><svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <use href="#icon_view" />
                          </svg></span>
                      </button>
                      <button class="pc__btn-wl bg-transparent border-0 js-add-wishlist" title="إضافة للمفضلة">
                        <svg width="16" height="16" viewBox="0 0 20 20" fill="none"
                          xmlns="http://www.w3.org/2000/svg">
                          <use href="#icon_heart" />
                        </svg>
                      </button>
                    </div>
                  </div>
                </div>
                <div class="swiper-slide product-card product-card_style3">
                  <div class="pc__img-wrapper">
                    <a href="details.html">
                      <img loading="lazy" src="{{ asset('assets/images/home/demo3/product-2-1.jpg') }}" width="258" height="313"
                        alt="Cropped Faux leather Jacket" class="pc__img">
                      <img loading="lazy" src="{{ asset('assets/images/home/demo3/product-2-2.jpg') }}" width="258" height="313"
                        alt="Cropped Faux leather Jacket" class="pc__img pc__img-second">
                    </a>
                  </div>

                  <div class="pc__info position-relative">
                    <h6 class="pc__title"><a href="details.html">عربة أطفال مريحة</a></h6>
                    <div class="product-card__price d-flex">
                      <span class="money price text-secondary">$62</span>
                    </div>

                    <div
                      class="anim_appear-bottom position-absolute bottom-0 start-0 d-none d-sm-flex align-items-center bg-body">
                      <button class="btn-link btn-link_lg me-4 text-uppercase fw-medium js-add-cart js-open-aside"
                        data-aside="cartDrawer" title="إضافة للسلة">إضافة للسلة</button>
                      <button class="btn-link btn-link_lg me-4 text-uppercase fw-medium js-quick-view"
                        data-bs-toggle="modal" data-bs-target="#quickView" title="عرض سريع">
                        <span class="d-none d-xxl-block">عرض سريع</span>
                        <span class="d-block d-xxl-none"><svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <use href="#icon_view" />
                          </svg></span>
                      </button>
                      <button class="pc__btn-wl bg-transparent border-0 js-add-wishlist" title="إضافة للمفضلة">
                        <svg width="16" height="16" viewBox="0 0 20 20" fill="none"
                          xmlns="http://www.w3.org/2000/svg">
                          <use href="#icon_heart" />
                        </svg>
                      </button>
                    </div>
                  </div>
                </div>
                <div class="swiper-slide product-card product-card_style3">
                  <div class="pc__img-wrapper">
                    <a href="details.html">
                      <img loading="lazy" src="{{ asset('assets/images/home/demo3/product-3-1.jpg') }}" width="258" height="313"
                        alt="Cropped Faux leather Jacket" class="pc__img">
                      <img loading="lazy" src="{{ asset('assets/images/home/demo3/product-3-2.jpg') }}" width="258" height="313"
                        alt="Cropped Faux leather Jacket" class="pc__img pc__img-second">
                    </a>
                  </div>

                  <div class="pc__info position-relative">
                    <h6 class="pc__title"><a href="details.html">كرسي طعام للأطفال</a></h6>
                    <div class="product-card__price d-flex align-items-center">
                      <span class="money price-old">$129</span>
                      <span class="money price text-secondary">$99</span>
                    </div>

                    <div
                      class="anim_appear-bottom position-absolute bottom-0 start-0 d-none d-sm-flex align-items-center bg-body">
                      <button class="btn-link btn-link_lg me-4 text-uppercase fw-medium js-add-cart js-open-aside"
                        data-aside="cartDrawer" title="إضافة للسلة">إضافة للسلة</button>
                      <button class="btn-link btn-link_lg me-4 text-uppercase fw-medium js-quick-view"
                        data-bs-toggle="modal" data-bs-target="#quickView" title="عرض سريع">
                        <span class="d-none d-xxl-block">عرض سريع</span>
                        <span class="d-block d-xxl-none"><svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <use href="#icon_view" />
                          </svg></span>
                      </button>
                      <button class="pc__btn-wl bg-transparent border-0 js-add-wishlist" title="إضافة للمفضلة">
                        <svg width="16" height="16" viewBox="0 0 20 20" fill="none"
                          xmlns="http://www.w3.org/2000/svg">
                          <use href="#icon_heart" />
                        </svg>
                      </button>
                    </div>
                  </div>
                </div>
              </div><!-- /.swiper-wrapper -->
            </div><!-- /.swiper-container js-swiper-slider -->
          </div><!-- /.position-relative -->
        </div>
      </div>
    </section>

    <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>

    <section class="category-banner container">
      <div class="row">
        <div class="col-md-6">
          <div class="category-banner__item border-radius-10 mb-5">
            <img loading="lazy" class="h-auto" src="{{ asset('assets/images/home/demo3/category_9.jpg') }}" width="690" height="665"
              alt="" />
            <div class="category-banner__item-mark">
              يبدأ من 19$
            </div>
            <div class="category-banner__item-content">
              <h3 class="mb-0">جاكيتات</h3>
              <a href="#" class="btn-link default-underline text-uppercase fw-medium">تسوق الآن</a>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="category-banner__item border-radius-10 mb-5">
            <img loading="lazy" class="h-auto" src="{{ asset('assets/images/home/demo3/category_10.jpg') }}" width="690" height="665"
              alt="" />
            <div class="category-banner__item-mark">
              يبدأ من 19$
            </div>
            <div class="category-banner__item-content">
              <h3 class="mb-0">ملابس رياضية</h3>
              <a href="#" class="btn-link default-underline text-uppercase fw-medium">تسوق الآن</a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>

    <section class="products-grid container">
      <h2 class="section-title text-center mb-5">المنتجات المميزة</h2>

      <div class="row">
        <div class="col-6 col-md-4 col-lg-3">
          <div class="product-card product-card_style3 mb-3 mb-md-4 mb-xxl-5">
            <div class="pc__img-wrapper">
              <a href="details.html">
                <img loading="lazy" src="{{ asset('assets/images/home/demo3/product-4.jpg') }}" width="330" height="400"
                  alt="فستان أطفال أنيق" class="pc__img">
              </a>
            </div>

            <div class="pc__info position-relative">
              <h6 class="pc__title"><a href="details.html">فستان أطفال أنيق</a></h6>
              <div class="product-card__price d-flex align-items-center">
                <span class="money price text-secondary">$29</span>
              </div>

              <div
                class="anim_appear-bottom position-absolute bottom-0 start-0 d-none d-sm-flex align-items-center bg-body">
                <button class="btn-link btn-link_lg me-4 text-uppercase fw-medium js-add-cart js-open-aside"
                  data-aside="cartDrawer" title="إضافة للسلة">إضافة للسلة</button>
                <button class="btn-link btn-link_lg me-4 text-uppercase fw-medium js-quick-view"
                  data-bs-toggle="modal" data-bs-target="#quickView" title="عرض سريع">
                  <span class="d-none d-xxl-block">عرض سريع</span>
                  <span class="d-block d-xxl-none"><svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                      xmlns="http://www.w3.org/2000/svg">
                      <use href="#icon_view" />
                    </svg></span>
                </button>
                <button class="pc__btn-wl bg-transparent border-0 js-add-wishlist" title="إضافة للمفضلة">
                  <svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <use href="#icon_heart" />
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
          <div class="product-card product-card_style3 mb-3 mb-md-4 mb-xxl-5">
            <div class="pc__img-wrapper">
              <a href="details.html">
                <img loading="lazy" src="{{ asset('assets/images/home/demo3/product-5.jpg') }}" width="330" height="400"
                  alt="قميص قطني مخطط" class="pc__img">
              </a>
            </div>

            <div class="pc__info position-relative">
              <h6 class="pc__title"><a href="details.html">قميص قطني مخطط</a></h6>
              <div class="product-card__price d-flex align-items-center">
                <span class="money price text-secondary">$62</span>
              </div>

              <div
                class="anim_appear-bottom position-absolute bottom-0 start-0 d-none d-sm-flex align-items-center bg-body">
                <button class="btn-link btn-link_lg me-4 text-uppercase fw-medium js-add-cart js-open-aside"
                  data-aside="cartDrawer" title="إضافة للسلة">إضافة للسلة</button>
                <button class="btn-link btn-link_lg me-4 text-uppercase fw-medium js-quick-view"
                  data-bs-toggle="modal" data-bs-target="#quickView" title="عرض سريع">
                  <span class="d-none d-xxl-block">عرض سريع</span>
                  <span class="d-block d-xxl-none"><svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                      xmlns="http://www.w3.org/2000/svg">
                      <use href="#icon_view" />
                    </svg></span>
                </button>
                <button class="pc__btn-wl bg-transparent border-0 js-add-wishlist" title="إضافة للمفضلة">
                  <svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <use href="#icon_heart" />
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
          <div class="product-card product-card_style3 mb-3 mb-md-4 mb-xxl-5">
            <div class="pc__img-wrapper">
              <a href="details.html">
                <img loading="lazy" src="{{ asset('assets/images/home/demo3/product-6.jpg') }}" width="330" height="400"
                  alt="طقم رياضي للأطفال" class="pc__img">
              </a>
              <div class="product-label text-uppercase bg-white top-0 left-0 mt-2 mx-2">جديد</div>
            </div>

            <div class="pc__info position-relative">
              <h6 class="pc__title"><a href="details.html">طقم رياضي للأطفال</a></h6>
              <div class="product-card__price d-flex align-items-center">
                <span class="money price text-secondary">$17</span>
              </div>

              <div
                class="anim_appear-bottom position-absolute bottom-0 start-0 d-none d-sm-flex align-items-center bg-body">
                <button class="btn-link btn-link_lg me-4 text-uppercase fw-medium js-add-cart js-open-aside"
                  data-aside="cartDrawer" title="إضافة للسلة">إضافة للسلة</button>
                <button class="btn-link btn-link_lg me-4 text-uppercase fw-medium js-quick-view"
                  data-bs-toggle="modal" data-bs-target="#quickView" title="عرض سريع">
                  <span class="d-none d-xxl-block">عرض سريع</span>
                  <span class="d-block d-xxl-none"><svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                      xmlns="http://www.w3.org/2000/svg">
                      <use href="#icon_view" />
                    </svg></span>
                </button>
                <button class="pc__btn-wl bg-transparent border-0 js-add-wishlist" title="إضافة للمفضلة">
                  <svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <use href="#icon_heart" />
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
          <div class="product-card product-card_style3 mb-3 mb-md-4 mb-xxl-5">
            <div class="pc__img-wrapper">
              <a href="details.html">
                <img loading="lazy" src="{{ asset('assets/images/home/demo3/product-7.jpg') }}" width="330" height="400"
                  alt="سترة صوفية دافئة" class="pc__img">
              </a>
              <div class="product-label bg-red text-white right-0 top-0 left-auto mt-2 mx-2">-67%</div>
            </div>

            <div class="pc__info position-relative">
              <h6 class="pc__title">سترة صوفية دافئة</h6>
              <div class="product-card__price d-flex align-items-center">
                <span class="money price-old">$129</span>
                <span class="money price text-secondary">$99</span>
              </div>

              <div
                class="anim_appear-bottom position-absolute bottom-0 start-0 d-none d-sm-flex align-items-center bg-body">
                <button class="btn-link btn-link_lg me-4 text-uppercase fw-medium js-add-cart js-open-aside"
                  data-aside="cartDrawer" title="إضافة للسلة">إضافة للسلة</button>
                <button class="btn-link btn-link_lg me-4 text-uppercase fw-medium js-quick-view"
                  data-bs-toggle="modal" data-bs-target="#quickView" title="عرض سريع">
                  <span class="d-none d-xxl-block">عرض سريع</span>
                  <span class="d-block d-xxl-none"><svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                      xmlns="http://www.w3.org/2000/svg">
                      <use href="#icon_view" />
                    </svg></span>
                </button>
                <button class="pc__btn-wl bg-transparent border-0 js-add-wishlist" title="إضافة للمفضلة">
                  <svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <use href="#icon_heart" />
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
          <div class="product-card product-card_style3 mb-3 mb-md-4 mb-xxl-5">
            <div class="pc__img-wrapper">
              <a href="details.html">
                <img loading="lazy" src="{{ asset('assets/images/home/demo3/product-8.jpg') }}" width="330" height="400"
                  alt="بنطال جينز أطفال" class="pc__img">
              </a>
            </div>

            <div class="pc__info position-relative">
              <h6 class="pc__title"><a href="details.html">بنطال جينز أطفال</a></h6>
              <div class="product-card__price d-flex align-items-center">
                <span class="money price text-secondary">$29</span>
              </div>

              <div
                class="anim_appear-bottom position-absolute bottom-0 start-0 d-none d-sm-flex align-items-center bg-body">
                <button class="btn-link btn-link_lg me-4 text-uppercase fw-medium js-add-cart js-open-aside"
                  data-aside="cartDrawer" title="إضافة للسلة">إضافة للسلة</button>
                <button class="btn-link btn-link_lg me-4 text-uppercase fw-medium js-quick-view"
                  data-bs-toggle="modal" data-bs-target="#quickView" title="عرض سريع">
                  <span class="d-none d-xxl-block">عرض سريع</span>
                  <span class="d-block d-xxl-none"><svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                      xmlns="http://www.w3.org/2000/svg">
                      <use href="#icon_view" />
                    </svg></span>
                </button>
                <button class="pc__btn-wl bg-transparent border-0 js-add-wishlist" title="إضافة للمفضلة">
                  <svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <use href="#icon_heart" />
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
          <div class="product-card product-card_style3 mb-3 mb-md-4 mb-xxl-5">
            <div class="pc__img-wrapper">
              <a href="details.html">
                <img loading="lazy" src="{{ asset('assets/images/home/demo3/product-9.jpg') }}" width="330" height="400"
                  alt="حذاء كاجوال مريح" class="pc__img">
              </a>
            </div>

            <div class="pc__info position-relative">
              <h6 class="pc__title"><a href="details.html">حذاء كاجوال مريح</a></h6>
              <div class="product-card__price d-flex align-items-center">
                <span class="money price text-secondary">$62</span>
              </div>

              <div
                class="anim_appear-bottom position-absolute bottom-0 start-0 d-none d-sm-flex align-items-center bg-body">
                <button class="btn-link btn-link_lg me-4 text-uppercase fw-medium js-add-cart js-open-aside"
                  data-aside="cartDrawer" title="إضافة للسلة">إضافة للسلة</button>
                <button class="btn-link btn-link_lg me-4 text-uppercase fw-medium js-quick-view"
                  data-bs-toggle="modal" data-bs-target="#quickView" title="عرض سريع">
                  <span class="d-none d-xxl-block">عرض سريع</span>
                  <span class="d-block d-xxl-none"><svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                      xmlns="http://www.w3.org/2000/svg">
                      <use href="#icon_view" />
                    </svg></span>
                </button>
                <button class="pc__btn-wl bg-transparent border-0 js-add-wishlist" title="إضافة للمفضلة">
                  <svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <use href="#icon_heart" />
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
          <div class="product-card product-card_style3 mb-3 mb-md-4 mb-xxl-5">
            <div class="pc__img-wrapper">
              <a href="details.html">
                <img loading="lazy" src="{{ asset('assets/images/home/demo3/product-10.jpg') }}" width="330" height="400"
                  alt="قبعة أطفال ملونة" class="pc__img">
              </a>
            </div>

            <div class="pc__info position-relative">
              <h6 class="pc__title"><a href="details.html">قبعة أطفال ملونة</a></h6>
              <div class="product-card__price d-flex align-items-center">
                <span class="money price text-secondary">$17</span>
              </div>

              <div
                class="anim_appear-bottom position-absolute bottom-0 start-0 d-none d-sm-flex align-items-center bg-body">
                <button class="btn-link btn-link_lg me-4 text-uppercase fw-medium js-add-cart js-open-aside"
                  data-aside="cartDrawer" title="إضافة للسلة">إضافة للسلة</button>
                <button class="btn-link btn-link_lg me-4 text-uppercase fw-medium js-quick-view"
                  data-bs-toggle="modal" data-bs-target="#quickView" title="عرض سريع">
                  <span class="d-none d-xxl-block">عرض سريع</span>
                  <span class="d-block d-xxl-none"><svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                      xmlns="http://www.w3.org/2000/svg">
                      <use href="#icon_view" />
                    </svg></span>
                </button>
                <button class="pc__btn-wl bg-transparent border-0 js-add-wishlist" title="إضافة للمفضلة">
                  <svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <use href="#icon_heart" />
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
          <div class="product-card product-card_style3 mb-3 mb-md-4 mb-xxl-5">
            <div class="pc__img-wrapper">
              <a href="details.html">
                <img loading="lazy" src="{{ asset('assets/images/home/demo3/product-11.jpg') }}" width="330" height="400"
                  alt="سويتر أطفال مضلع" class="pc__img">
              </a>
            </div>

            <div class="pc__info position-relative">
              <h6 class="pc__title">سويتر أطفال مضلع</h6>
              <div class="product-card__price d-flex align-items-center">
                <span class="money price-old">$129</span>
                <span class="money price text-secondary">$99</span>
              </div>

              <div
                class="anim_appear-bottom position-absolute bottom-0 start-0 d-none d-sm-flex align-items-center bg-body">
                <button class="btn-link btn-link_lg me-4 text-uppercase fw-medium js-add-cart js-open-aside"
                  data-aside="cartDrawer" title="إضافة للسلة">إضافة للسلة</button>
                <button class="btn-link btn-link_lg me-4 text-uppercase fw-medium js-quick-view"
                  data-bs-toggle="modal" data-bs-target="#quickView" title="عرض سريع">
                  <span class="d-none d-xxl-block">عرض سريع</span>
                  <span class="d-block d-xxl-none"><svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                      xmlns="http://www.w3.org/2000/svg">
                      <use href="#icon_view" />
                    </svg></span>
                </button>
                <button class="pc__btn-wl bg-transparent border-0 js-add-wishlist" title="إضافة للمفضلة">
                  <svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <use href="#icon_heart" />
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div><!-- /.row -->

      <div class="text-center mt-2">
        <a class="btn-link btn-link_lg default-underline text-uppercase fw-medium" href="{{ route('shop.index') }}">تحميل المزيد</a>
      </div>
    </section>

    <!-- Brands Section -->
    @if($brands->count() > 0)
    <section class="brands-carousel container">
      <h2 class="section-title text-center mb-5">العلامات التجارية المميزة</h2>

      <div class="position-relative">
        <div class="swiper-container js-swiper-slider" data-settings='{
              "autoplay": {
                "delay": 3000
              },
              "slidesPerView": 2,
              "slidesPerGroup": 1,
              "effect": "none",
              "loop": true,
              "breakpoints": {
                "768": {
                  "slidesPerView": 4
                },
                "992": {
                  "slidesPerView": 6
                }
              }
            }'>
          <div class="swiper-wrapper">
            @foreach($brands as $brand)
            <div class="swiper-slide">
              <div class="text-center p-3">
                <img loading="lazy" class="w-100 h-auto mb-3"
                  src="{{ asset('uploads/brands/' . $brand->image) }}?v={{ time() }}"
                  width="124" height="124"
                  alt="{{ $brand->name }}"
                  onerror="this.src='{{ asset('assets/images/home/demo3/category_1.png') }}'" />
                <div class="text-center">
                  <a href="{{ route('shop.brand', $brand->slug) }}" class="menu-link fw-medium">{{ $brand->name }}</a>
                </div>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </section>
    @endif
  </div>

  <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>

</main>
@endsection