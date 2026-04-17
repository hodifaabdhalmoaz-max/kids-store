<footer class="site-footer pt-5 pb-3">
    <div class="container">
        <div class="row g-4 text-end">
            <!-- العمود الأول (الأيمن): شعار المتجر وبيانات التواصل -->
            <div class="col-lg-4 col-md-12 order-lg-1">
                <div class="footer-section">
                    <div class="footer-logo mb-4 text-center text-lg-end">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="دنيا الأطفال" class="img-fluid" style="max-height: 120px;">
                    </div>
                    <p class="text-light-50 mb-4 text-center text-lg-end">متجرك المتخصص في منتجات الأطفال عالية الجودة. نوفر كل ما يحتاجه طفلك من ملابس وألعاب ومستلزمات بأفضل الأسعار وبجودة تليق بهم.</p>
                    
                    <div class="contact-details">
                        <div class="d-flex align-items-center justify-content-center justify-content-lg-start mb-3">
                            <div class="contact-icon bg-warning rounded-circle p-2 ms-3">
                                <i data-lucide="mail" class="text-dark" style="width: 18px; height: 18px;"></i>
                            </div>
                            <div class="contact-text">
                                <span class="d-block text-muted small">البريد الإلكتروني</span>
                                <a href="mailto:hodifaabdhalmoaz@gmail.com" class="text-white text-decoration-none">hodifaabdhalmoaz@gmail.com</a>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-center justify-content-lg-start mb-4">
                            <div class="contact-icon bg-warning rounded-circle p-2 ms-3">
                                <i data-lucide="phone" class="text-dark" style="width: 18px; height: 18px;"></i>
                            </div>
                            <div class="contact-text">
                                <span class="d-block text-muted small">هاتف 1: <a href="tel:+967777548421" class="text-white text-decoration-none">+967 777-548-421</a></span>
                                <span class="d-block text-muted small">هاتف 2: <a href="tel:+967718706242" class="text-white text-decoration-none">+967 718-706-242</a></span>
                            </div>
                        </div>
                    </div>

                    <div class="social-links d-flex justify-content-center justify-content-lg-start gap-2">
                        <a href="https://www.facebook.com/profile.php?id=61558122398516" target="_blank" class="social-btn facebook" title="فيسبوك">
                            <i data-lucide="facebook"></i>
                        </a>
                        <a href="https://www.instagram.com/dunya_alatfaal" target="_blank" class="social-btn instagram" title="إنستقرام">
                            <i data-lucide="instagram"></i>
                        </a>
                        <a href="https://wa.me/message/R74CYLSGZQD7C1" target="_blank" class="social-btn whatsapp" title="واتساب">
                            <i data-lucide="message-circle"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- العمود الثاني: الشركة -->
            <div class="col-lg-2 col-md-6 order-lg-2">
                <div class="footer-section">
                    <h5 class="footer-title">الشركة</h5>
                    <ul class="list-unstyled footer-links">
                        <li><a href="{{ route('about') }}">من نحن</a></li>
                        <li><a href="{{ route('contact') }}">اتصل بنا</a></li>
                        <li><a href="{{ route('faq') }}">الأسئلة الشائعة</a></li>
                        <li><a href="{{ route('shipping') }}">معلومات الشحن</a></li>
                        <li><a href="{{ route('returns') }}">سياسة الإرجاع</a></li>
                    </ul>
                </div>
            </div>

            <!-- العمود الثالث: المتجر -->
            <div class="col-lg-2 col-md-6 order-lg-3">
                <div class="footer-section">
                    <h5 class="footer-title">المتجر</h5>
                    <ul class="list-unstyled footer-links">
                        <li><a href="{{ route('shop.index') }}">جميع المنتجات</a></li>
                        <li><a href="{{ route('shop.index', ['sort' => 'latest']) }}">وصل حديثاً</a></li>
                        <li><a href="{{ route('shop.index', ['sort' => 'featured']) }}">المنتجات المميزة</a></li>
                        <li><a href="{{ route('cart.index') }}">سلة التسوق</a></li>
                        <li><a href="{{ route('wishlist.index') }}">قائمة الأمنيات</a></li>
                    </ul>
                </div>
            </div>

            <!-- العمود الرابع: المساعدة -->
            <div class="col-lg-2 col-md-6 order-lg-4">
                <div class="footer-section">
                    <h5 class="footer-title">المساعدة</h5>
                    <ul class="list-unstyled footer-links">
                        <li><a href="{{ route('contact') }}">خدمة العملاء</a></li>
                        <li>
                            @auth
                                <a href="{{ Auth::user()->utype === 'ADM' ? route('admin.index') : route('user.index') }}">حسابي</a>
                            @else
                                <a href="{{ route('login') }}">تسجيل الدخول</a>
                            @endauth
                        </li>
                        <li><a href="{{ route('privacy') }}">سياسة الخصوصية</a></li>
                        <li><a href="{{ route('terms') }}">الشروط والأحكام</a></li>
                        <li><a href="{{ route('faq') }}">الأسئلة الشائعة</a></li>
                    </ul>
                </div>
            </div>

            <!-- العمود الخامس: فئات الأطفال -->
            <div class="col-lg-2 col-md-6 order-lg-5">
                <div class="footer-section">
                    <h5 class="footer-title">فئات الأطفال</h5>
                    <ul class="list-unstyled footer-links">
                        <li><a href="{{ route('shop.index', ['category' => 'ملابس-اطفال']) }}">ملابس أطفال</a></li>
                        <li><a href="{{ route('shop.index', ['category' => 'العاب-اطفال']) }}">ألعاب أطفال</a></li>
                        <li><a href="{{ route('shop.index', ['category' => 'احذية-اطفال']) }}">أحذية أطفال</a></li>
                        <li><a href="{{ route('shop.index', ['category' => 'اكسسوارات-اطفال']) }}">إكسسوارات أطفال</a></li>
                        <li><a href="{{ route('shop.index', ['category' => 'مستلزمات-اطفال']) }}">مستلزمات أطفال</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- الشريط السفلي للحقوق -->
        <div class="footer-bottom mt-5 pt-4">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <p class="mb-0 text-muted">
                        &copy; {{ date('Y') }} <span class="text-warning">دنيا الأطفال</span>. جميع الحقوق محفوظة.
                    </p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <div class="developer-credit d-inline-flex align-items-center gap-2">
                        <span class="text-muted small">تطوير بواسطة:</span>
                        <a href="https://hodifatech.com" target="_blank" class="developer-name text-decoration-none">هذيفة الحديفي</a>
                        <div class="dev-socials ms-2">
                            <a href="https://www.linkedin.com/in/hodifa-al-hodify-30644b289" target="_blank" class="dev-icon" title="LinkedIn"><i data-lucide="linkedin"></i></a>
                            <a href="https://github.com/HA1234098765" target="_blank" class="dev-icon" title="GitHub"><i data-lucide="github"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        :root {
            --footer-bg: #1a1a1a;
            --footer-accent: #ffc107;
            --link-hover: #ffffff;
            --social-bg: rgba(255, 255, 255, 0.05);
        }

        .site-footer {
            background: var(--footer-bg);
            color: #ffffff;
            font-family: 'Outfit', 'Cairo', sans-serif;
            border-top: 4px solid var(--footer-accent);
        }

        .footer-title {
            color: var(--footer-accent);
            font-weight: 700;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 10px;
        }

        .footer-title::after {
            content: '';
            position: absolute;
            right: 0;
            bottom: 0;
            width: 30px;
            height: 2px;
            background: var(--footer-accent);
        }

        .footer-links li {
            margin-bottom: 0.8rem;
        }

        .footer-links a {
            color: #d1d1d1;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .footer-links a:hover {
            color: var(--link-hover);
            transform: translateX(-5px);
        }

        .text-light-50 {
            color: rgba(255, 255, 255, 0.6);
            line-height: 1.6;
        }

        .contact-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            flex-shrink: 0;
            margin-left: 10px; /* Adjusting for RTL */
        }

        .social-btn {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--social-bg);
            color: #fff;
            border-radius: 10px;
            text-decoration: none;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
        }

        .social-btn:hover {
            background: var(--footer-accent);
            color: var(--footer-bg);
            transform: translateY(-5px);
        }

        .social-btn.facebook:hover { background: #3b5998; color: white; }
        .social-btn.instagram:hover { background: #e1306c; color: white; }
        .social-btn.whatsapp:hover { background: #25d366; color: white; }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .developer-name {
            color: #fff;
            font-weight: 600;
            transition: color 0.3s;
        }

        .developer-name:hover {
            color: var(--footer-accent);
        }

        .dev-icon {
            color: #888;
            margin: 0 5px;
            font-size: 0.8rem;
            transition: color 0.3s;
        }

        .dev-icon i {
            width: 14px;
            height: 14px;
        }

        .dev-icon:hover {
            color: #fff;
        }

        /* RTL Logic fixes */
        [dir="rtl"] .footer-links a:hover {
            transform: translateX(-5px);
        }
        
        [dir="rtl"] .contact-icon {
            margin-right: 0;
            margin-left: 15px;
        }

        @media (max-width: 991px) {
            .footer-section {
                text-align: center;
                margin-bottom: 2rem;
            }
            .footer-title::after {
                right: 50%;
                transform: translateX(50%);
            }
            .social-links {
                justify-content: center;
            }
            .contact-details .d-flex {
                justify-content: center;
            }
        }
    </style>
</footer>
