@extends('layouts.app')

@section('title', 'الشروط والأحكام - متجر الأطفال')
@section('description', 'الشروط والأحكام الخاصة بمتجر الأطفال - اقرأ شروط الاستخدام والأحكام قبل التسوق')
@section('meta_description', 'الشروط والأحكام الخاصة بمتجر الأطفال - اقرأ شروط الاستخدام والأحكام قبل التسوق')

@push('styles')
<style>
.terms-hero {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
    padding: 80px 0;
    position: relative;
    overflow: hidden;
}

.terms-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="terms-pattern" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23terms-pattern)"/></svg>');
}

.terms-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.08);
    padding: 40px;
    margin-bottom: 30px;
    border: 1px solid #e8f4f8;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.terms-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: linear-gradient(to bottom, #4facfe, #00f2fe);
}

.terms-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 25px 50px rgba(0,0,0,0.12);
}

.terms-icon {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #4facfe, #00f2fe);
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 25px;
    color: white;
    box-shadow: 0 8px 25px rgba(79, 172, 254, 0.3);
}

.terms-section-title {
    color: #1a365d;
    font-weight: 700;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    font-size: 1.4rem;
}

.terms-content {
    color: #4a5568;
    line-height: 1.8;
    font-size: 16px;
}

.terms-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.terms-list li {
    padding: 15px 0;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: flex-start;
    gap: 15px;
    transition: all 0.3s ease;
}

.terms-list li:last-child {
    border-bottom: none;
}

.terms-list li:hover {
    background: rgba(79, 172, 254, 0.05);
    padding-left: 10px;
    border-radius: 8px;
}

.terms-list .list-icon {
    margin-top: 3px;
    flex-shrink: 0;
}

.breadcrumb-terms {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(15px);
    border-radius: 50px;
    padding: 15px 30px;
    margin-bottom: 40px;
    display: inline-flex;
    align-items: center;
    gap: 12px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    border: 1px solid rgba(255,255,255,0.2);
}

.breadcrumb-terms a {
    color: #4facfe;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s ease;
}

.breadcrumb-terms a:hover {
    color: #00f2fe;
}

.terms-highlight {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    padding: 40px;
    text-align: center;
    color: white;
    margin: 50px 0;
    position: relative;
    overflow: hidden;
}

.terms-highlight::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: pulse 4s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); opacity: 0.5; }
    50% { transform: scale(1.1); opacity: 0.8; }
}

.btn-terms {
    background: white;
    color: #4facfe;
    border: none;
    padding: 15px 35px;
    border-radius: 50px;
    font-weight: 600;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.btn-terms:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.2);
    color: #00f2fe;
}

.section-divider {
    height: 3px;
    background: linear-gradient(90deg, #4facfe, #00f2fe);
    border-radius: 2px;
    margin: 40px 0;
    opacity: 0.3;
}

@media (max-width: 768px) {
    .terms-hero {
        padding: 60px 0;
    }

    .terms-card {
        padding: 25px;
        margin-bottom: 20px;
    }

    .terms-icon {
        width: 60px;
        height: 60px;
    }

    .terms-section-title {
        font-size: 1.2rem;
    }
}
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="terms-hero">
    <div class="container position-relative">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <div class="breadcrumb-terms">
                    <a href="{{ route('home.index') }}">
                        <i data-lucide="home" style="width: 18px; height: 18px;"></i>
                        الرئيسية
                    </a>
                    <i data-lucide="chevron-left" style="width: 16px; height: 16px; color: #a0aec0;"></i>
                    <span style="color: #1a365d; font-weight: 700;">الشروط والأحكام</span>
                </div>

                <h1 class="display-4 fw-bold mb-4">الشروط والأحكام</h1>
                <p class="lead mb-0">اقرأ شروط الاستخدام والأحكام الخاصة بمتجرنا لضمان تجربة تسوق آمنة ومريحة</p>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<main class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <!-- General Terms Section -->
                <div class="terms-card">
                    <div class="terms-icon">
                        <i data-lucide="file-text" style="width: 28px; height: 28px;"></i>
                    </div>
                    <h3 class="terms-section-title">
                        <i data-lucide="info" style="width: 22px; height: 22px; color: #4facfe;"></i>
                        الشروط العامة للاستخدام
                    </h3>
                    <div class="terms-content">
                        <p class="mb-4">باستخدام موقعنا الإلكتروني، فإنك توافق على الالتزام بهذه الشروط والأحكام:</p>
                        <ul class="terms-list">
                            <li>
                                <i data-lucide="user-check" class="list-icon" style="width: 20px; height: 20px; color: #48bb78;"></i>
                                <div>
                                    <strong>العمر القانوني:</strong> يجب أن تكون بالغاً من العمر 18 عاماً أو أكثر لاستخدام خدماتنا
                                </div>
                            </li>
                            <li>
                                <i data-lucide="shield-check" class="list-icon" style="width: 20px; height: 20px; color: #48bb78;"></i>
                                <div>
                                    <strong>دقة المعلومات:</strong> تتعهد بتقديم معلومات صحيحة ودقيقة عند التسجيل أو الشراء
                                </div>
                            </li>
                            <li>
                                <i data-lucide="lock" class="list-icon" style="width: 20px; height: 20px; color: #48bb78;"></i>
                                <div>
                                    <strong>أمان الحساب:</strong> أنت مسؤول عن الحفاظ على سرية معلومات حسابك وكلمة المرور
                                </div>
                            </li>
                            <li>
                                <i data-lucide="gavel" class="list-icon" style="width: 20px; height: 20px; color: #48bb78;"></i>
                                <div>
                                    <strong>الالتزام بالقوانين:</strong> يجب استخدام الموقع بما يتوافق مع القوانين المحلية والدولية
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Products and Services Section -->
                <div class="terms-card">
                    <div class="terms-icon">
                        <i data-lucide="package" style="width: 28px; height: 28px;"></i>
                    </div>
                    <h3 class="terms-section-title">
                        <i data-lucide="shopping-bag" style="width: 22px; height: 22px; color: #4facfe;"></i>
                        المنتجات والخدمات
                    </h3>
                    <div class="terms-content">
                        <p class="mb-4">شروط وأحكام المنتجات والخدمات المقدمة في متجرنا:</p>
                        <ul class="terms-list">
                            <li>
                                <i data-lucide="check-circle" class="list-icon" style="width: 20px; height: 20px; color: #ed8936;"></i>
                                <div>جميع المنتجات خاضعة للتوفر في المخزون</div>
                            </li>
                            <li>
                                <i data-lucide="edit" class="list-icon" style="width: 20px; height: 20px; color: #ed8936;"></i>
                                <div>نحتفظ بالحق في تعديل أو إيقاف أي منتج دون إشعار مسبق</div>
                            </li>
                            <li>
                                <i data-lucide="image" class="list-icon" style="width: 20px; height: 20px; color: #ed8936;"></i>
                                <div>الصور المعروضة قد تختلف قليلاً عن المنتج الفعلي</div>
                            </li>
                            <li>
                                <i data-lucide="award" class="list-icon" style="width: 20px; height: 20px; color: #ed8936;"></i>
                                <div>جميع المنتجات مضمونة الجودة وتخضع لمعايير السلامة</div>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Orders and Payments Section -->
                <div class="terms-card">
                    <div class="terms-icon">
                        <i data-lucide="credit-card" style="width: 28px; height: 28px;"></i>
                    </div>
                    <h3 class="terms-section-title">
                        <i data-lucide="dollar-sign" style="width: 22px; height: 22px; color: #4facfe;"></i>
                        الطلبات والمدفوعات
                    </h3>
                    <div class="terms-content">
                        <p class="mb-4">شروط وأحكام الطلبات والدفع في متجرنا:</p>
                        <ul class="terms-list">
                            <li>
                                <i data-lucide="banknote" class="list-icon" style="width: 20px; height: 20px; color: #9f7aea;"></i>
                                <div>جميع الأسعار بالريال اليمني ما لم يُذكر خلاف ذلك</div>
                            </li>
                            <li>
                                <i data-lucide="clock" class="list-icon" style="width: 20px; height: 20px; color: #9f7aea;"></i>
                                <div>الدفع مطلوب عند تأكيد الطلب أو عند الاستلام</div>
                            </li>
                            <li>
                                <i data-lucide="x-circle" class="list-icon" style="width: 20px; height: 20px; color: #9f7aea;"></i>
                                <div>نحتفظ بالحق في إلغاء الطلبات في حالة عدم التوفر</div>
                            </li>
                            <li>
                                <i data-lucide="shield" class="list-icon" style="width: 20px; height: 20px; color: #9f7aea;"></i>
                                <div>جميع المعاملات المالية محمية ومؤمنة</div>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Shipping and Delivery Section -->
                <div class="terms-card">
                    <div class="terms-icon">
                        <i data-lucide="truck" style="width: 28px; height: 28px;"></i>
                    </div>
                    <h3 class="terms-section-title">
                        <i data-lucide="map-pin" style="width: 22px; height: 22px; color: #4facfe;"></i>
                        الشحن والتوصيل
                    </h3>
                    <div class="terms-content">
                        <p class="mb-4">سياسة الشحن والتوصيل:</p>
                        <ul class="terms-list">
                            <li>
                                <i data-lucide="calendar" class="list-icon" style="width: 20px; height: 20px; color: #38b2ac;"></i>
                                <div>مدة التوصيل من 2-5 أيام عمل داخل صنعاء</div>
                            </li>
                            <li>
                                <i data-lucide="map" class="list-icon" style="width: 20px; height: 20px; color: #38b2ac;"></i>
                                <div>التوصيل متاح لجميع محافظات اليمن</div>
                            </li>
                            <li>
                                <i data-lucide="phone" class="list-icon" style="width: 20px; height: 20px; color: #38b2ac;"></i>
                                <div>سيتم التواصل معك قبل التوصيل لتأكيد الموعد</div>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Returns and Refunds Section -->
                <div class="terms-card">
                    <div class="terms-icon">
                        <i data-lucide="rotate-ccw" style="width: 28px; height: 28px;"></i>
                    </div>
                    <h3 class="terms-section-title">
                        <i data-lucide="undo" style="width: 22px; height: 22px; color: #4facfe;"></i>
                        الإرجاع والاسترداد
                    </h3>
                    <div class="terms-content">
                        <p class="mb-4">سياسة الإرجاع والاسترداد:</p>
                        <ul class="terms-list">
                            <li>
                                <i data-lucide="calendar-days" class="list-icon" style="width: 20px; height: 20px; color: #f56565;"></i>
                                <div>إمكانية الإرجاع خلال 7 أيام من تاريخ الاستلام</div>
                            </li>
                            <li>
                                <i data-lucide="package" class="list-icon" style="width: 20px; height: 20px; color: #f56565;"></i>
                                <div>المنتج يجب أن يكون في حالته الأصلية وغير مستخدم</div>
                            </li>
                            <li>
                                <i data-lucide="refresh-cw" class="list-icon" style="width: 20px; height: 20px; color: #f56565;"></i>
                                <div>استرداد كامل أو استبدال حسب رغبة العميل</div>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- User Responsibilities Section -->
                <div class="terms-card">
                    <div class="terms-icon">
                        <i data-lucide="user-check" style="width: 28px; height: 28px;"></i>
                    </div>
                    <h3 class="terms-section-title">
                        <i data-lucide="clipboard-check" style="width: 22px; height: 22px; color: #4facfe;"></i>
                        {{ __('messages.terms_user_title') }}
                    </h3>
                    <div class="terms-content">
                        <p class="mb-4">{{ __('messages.terms_user_content') }}</p>
                        <ul class="terms-list">
                            <li>
                                <i data-lucide="check-circle" class="list-icon" style="width: 20px; height: 20px; color: #48bb78;"></i>
                                <div>{{ __('messages.terms_user_point_1') }}</div>
                            </li>
                            <li>
                                <i data-lucide="check-circle" class="list-icon" style="width: 20px; height: 20px; color: #48bb78;"></i>
                                <div>{{ __('messages.terms_user_point_2') }}</div>
                            </li>
                            <li>
                                <i data-lucide="check-circle" class="list-icon" style="width: 20px; height: 20px; color: #48bb78;"></i>
                                <div>{{ __('messages.terms_user_point_3') }}</div>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Privacy Section -->
                <div class="terms-card">
                    <div class="terms-icon">
                        <i data-lucide="shield" style="width: 28px; height: 28px;"></i>
                    </div>
                    <h3 class="terms-section-title">
                        <i data-lucide="eye-off" style="width: 22px; height: 22px; color: #4facfe;"></i>
                        {{ __('messages.terms_privacy_title') }}
                    </h3>
                    <div class="terms-content">
                        <p class="mb-4">{{ __('messages.terms_privacy_content') }}</p>
                        <a href="{{ route('privacy') }}" class="btn-terms">
                            <i data-lucide="external-link" style="width: 18px; height: 18px;"></i>
                            {{ __('messages.read_privacy_policy') }}
                        </a>
                    </div>
                </div>

                <!-- Limitation of Liability Section -->
                <div class="terms-card">
                    <div class="terms-icon">
                        <i data-lucide="alert-triangle" style="width: 28px; height: 28px;"></i>
                    </div>
                    <h3 class="terms-section-title">
                        <i data-lucide="info" style="width: 22px; height: 22px; color: #4facfe;"></i>
                        {{ __('messages.terms_liability_title') }}
                    </h3>
                    <div class="terms-content">
                        <p>{{ __('messages.terms_liability_content') }}</p>
                    </div>
                </div>

                <!-- Changes to Terms Section -->
                <div class="terms-card">
                    <div class="terms-icon">
                        <i data-lucide="refresh-cw" style="width: 28px; height: 28px;"></i>
                    </div>
                    <h3 class="terms-section-title">
                        <i data-lucide="edit" style="width: 22px; height: 22px; color: #4facfe;"></i>
                        {{ __('messages.terms_changes_title') }}
                    </h3>
                    <div class="terms-content">
                        <p>{{ __('messages.terms_changes_content') }}</p>
                    </div>
                </div>

                <!-- Contact Highlight -->
                <div class="terms-highlight">
                    <h4 class="mb-3">
                        <i data-lucide="help-circle" style="width: 24px; height: 24px; margin-left: 10px;"></i>
                        هل لديك أسئلة حول الشروط والأحكام؟
                    </h4>
                    <p class="mb-4">فريقنا القانوني جاهز للإجابة على جميع استفساراتك وتوضيح أي بند تحتاج لمزيد من التفاصيل</p>
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="mailto:hodifaabdhalmoaz@gmail.com" class="btn-terms">
                            <i data-lucide="mail" style="width: 18px; height: 18px;"></i>
                            راسلنا عبر البريد
                        </a>
                        <a href="{{ route('contact') }}" class="btn-terms">
                            <i data-lucide="message-square" style="width: 18px; height: 18px;"></i>
                            صفحة الاتصال
                        </a>
                    </div>
                    <div class="section-divider"></div>
                    <p class="small mb-0 opacity-75">
                        <i data-lucide="calendar" style="width: 16px; height: 16px; margin-left: 5px;"></i>
                        آخر تحديث: {{ date('d/m/Y') }}
                    </p>
                </div>

            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    // Add smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Add fade-in animation to cards
    const cards = document.querySelectorAll('.terms-card');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    });

    cards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });
});
</script>
@endpush
