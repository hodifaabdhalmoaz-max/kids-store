@extends('layouts.app')

@section('title', 'سياسة الخصوصية - متجر الأطفال')
@section('description', 'سياسة الخصوصية الخاصة بمتجر الأطفال - نحن نحترم خصوصيتك ونحمي بياناتك الشخصية')
@section('meta_description', 'سياسة الخصوصية الخاصة بمتجر الأطفال - نحن نحترم خصوصيتك ونحمي بياناتك الشخصية')

@push('styles')
<style>
.privacy-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 80px 0;
    position: relative;
    overflow: hidden;
}

.privacy-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="1" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="1" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
}

.privacy-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    padding: 40px;
    margin-bottom: 30px;
    border: 1px solid #f0f0f0;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.privacy-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: linear-gradient(to bottom, #667eea, #764ba2);
}

.privacy-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 30px 60px rgba(0,0,0,0.15);
}

.privacy-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
    color: white;
}

.privacy-section-title {
    color: #2d3748;
    font-weight: 700;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 15px;
}

.privacy-content {
    color: #4a5568;
    line-height: 1.8;
    font-size: 16px;
}

.privacy-list {
    list-style: none;
    padding: 0;
}

.privacy-list li {
    padding: 12px 0;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: flex-start;
    gap: 15px;
}

.privacy-list li:last-child {
    border-bottom: none;
}

.privacy-list .check-icon {
    color: #48bb78;
    margin-top: 2px;
    flex-shrink: 0;
}

.breadcrumb-modern {
    background: rgba(255,255,255,0.9);
    backdrop-filter: blur(10px);
    border-radius: 50px;
    padding: 12px 24px;
    margin-bottom: 40px;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.breadcrumb-modern a {
    color: #667eea;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s ease;
}

.breadcrumb-modern a:hover {
    color: #764ba2;
}

.contact-cta {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    padding: 40px;
    text-align: center;
    color: white;
    margin-top: 50px;
}

.btn-modern {
    background: white;
    color: #667eea;
    border: none;
    padding: 12px 30px;
    border-radius: 50px;
    font-weight: 600;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 10px;
}

.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    color: #764ba2;
}

@media (max-width: 768px) {
    .privacy-hero {
        padding: 60px 0;
    }

    .privacy-card {
        padding: 25px;
        margin-bottom: 20px;
    }

    .privacy-icon {
        width: 50px;
        height: 50px;
    }
}
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="privacy-hero">
    <div class="container position-relative">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <div class="breadcrumb-modern">
                    <a href="{{ route('home.index') }}">
                        <i data-lucide="home" style="width: 16px; height: 16px;"></i>
                        الرئيسية
                    </a>
                    <i data-lucide="chevron-left" style="width: 16px; height: 16px; color: #a0aec0;"></i>
                    <span style="color: #2d3748; font-weight: 600;">سياسة الخصوصية</span>
                </div>

                <h1 class="display-4 fw-bold mb-4">سياسة الخصوصية</h1>
                <p class="lead mb-0">نحن نحترم خصوصيتك ونلتزم بحماية بياناتك الشخصية وفقاً لأعلى معايير الأمان</p>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<main class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <!-- Data Collection Section -->
                <div class="privacy-card">
                    <div class="privacy-icon">
                        <i data-lucide="database" style="width: 24px; height: 24px;"></i>
                    </div>
                    <h3 class="privacy-section-title">
                        <i data-lucide="info" style="width: 20px; height: 20px; color: #667eea;"></i>
                        المعلومات التي نجمعها
                    </h3>
                    <div class="privacy-content">
                        <p class="mb-4">نقوم بجمع المعلومات التالية لتحسين خدماتنا وضمان تجربة تسوق آمنة:</p>
                        <ul class="privacy-list">
                            <li>
                                <i data-lucide="check-circle" class="check-icon" style="width: 18px; height: 18px;"></i>
                                <div>
                                    <strong>المعلومات الشخصية:</strong> الاسم، البريد الإلكتروني، رقم الهاتف، والعنوان
                                </div>
                            </li>
                            <li>
                                <i data-lucide="check-circle" class="check-icon" style="width: 18px; height: 18px;"></i>
                                <div>
                                    <strong>معلومات الطلبات:</strong> تفاصيل المنتجات المشتراة وتاريخ الطلبات
                                </div>
                            </li>
                            <li>
                                <i data-lucide="check-circle" class="check-icon" style="width: 18px; height: 18px;"></i>
                                <div>
                                    <strong>معلومات تقنية:</strong> عنوان IP، نوع المتصفح، ونظام التشغيل
                                </div>
                            </li>
                            <li>
                                <i data-lucide="check-circle" class="check-icon" style="width: 18px; height: 18px;"></i>
                                <div>
                                    <strong>ملفات تعريف الارتباط:</strong> لتحسين تجربة التصفح وحفظ التفضيلات
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Data Usage Section -->
                <div class="privacy-card">
                    <div class="privacy-icon">
                        <i data-lucide="settings" style="width: 24px; height: 24px;"></i>
                    </div>
                    <h3 class="privacy-section-title">
                        <i data-lucide="target" style="width: 20px; height: 20px; color: #667eea;"></i>
                        كيف نستخدم معلوماتك
                    </h3>
                    <div class="privacy-content">
                        <p class="mb-4">نستخدم المعلومات المجمعة للأغراض التالية:</p>
                        <ul class="privacy-list">
                            <li>
                                <i data-lucide="shopping-cart" style="width: 18px; height: 18px; color: #48bb78;"></i>
                                <div>معالجة وتنفيذ طلباتك بدقة وسرعة</div>
                            </li>
                            <li>
                                <i data-lucide="headphones" style="width: 18px; height: 18px; color: #48bb78;"></i>
                                <div>تقديم خدمة عملاء متميزة والرد على استفساراتك</div>
                            </li>
                            <li>
                                <i data-lucide="mail" style="width: 18px; height: 18px; color: #48bb78;"></i>
                                <div>إرسال تحديثات الطلبات والعروض الخاصة</div>
                            </li>
                            <li>
                                <i data-lucide="shield-check" style="width: 18px; height: 18px; color: #48bb78;"></i>
                                <div>منع الاحتيال وضمان أمان المعاملات</div>
                            </li>
                            <li>
                                <i data-lucide="trending-up" style="width: 18px; height: 18px; color: #48bb78;"></i>
                                <div>تحليل وتحسين خدماتنا ومنتجاتنا</div>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Data Protection Section -->
                <div class="privacy-card">
                    <div class="privacy-icon">
                        <i data-lucide="shield" style="width: 24px; height: 24px;"></i>
                    </div>
                    <h3 class="privacy-section-title">
                        <i data-lucide="lock" style="width: 20px; height: 20px; color: #667eea;"></i>
                        حماية البيانات والأمان
                    </h3>
                    <div class="privacy-content">
                        <p class="mb-4">نطبق أعلى معايير الأمان لحماية معلوماتك الشخصية:</p>
                        <ul class="privacy-list">
                            <li>
                                <i data-lucide="key" style="width: 18px; height: 18px; color: #ed8936;"></i>
                                <div>تشفير SSL/TLS لجميع البيانات المنقولة</div>
                            </li>
                            <li>
                                <i data-lucide="server" style="width: 18px; height: 18px; color: #ed8936;"></i>
                                <div>خوادم آمنة ومحمية بجدران حماية متقدمة</div>
                            </li>
                            <li>
                                <i data-lucide="users" style="width: 18px; height: 18px; color: #ed8936;"></i>
                                <div>وصول محدود للموظفين المخولين فقط</div>
                            </li>
                            <li>
                                <i data-lucide="refresh-cw" style="width: 18px; height: 18px; color: #ed8936;"></i>
                                <div>مراجعة وتحديث دوري للأنظمة الأمنية</div>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- User Rights Section -->
                <div class="privacy-card">
                    <div class="privacy-icon">
                        <i data-lucide="user-check" style="width: 24px; height: 24px;"></i>
                    </div>
                    <h3 class="privacy-section-title">
                        <i data-lucide="clipboard-list" style="width: 20px; height: 20px; color: #667eea;"></i>
                        حقوقك كمستخدم
                    </h3>
                    <div class="privacy-content">
                        <p class="mb-4">لديك الحقوق التالية فيما يتعلق ببياناتك الشخصية:</p>
                        <ul class="privacy-list">
                            <li>
                                <i data-lucide="eye" style="width: 18px; height: 18px; color: #9f7aea;"></i>
                                <div>الحق في الوصول إلى بياناتك الشخصية</div>
                            </li>
                            <li>
                                <i data-lucide="edit" style="width: 18px; height: 18px; color: #9f7aea;"></i>
                                <div>الحق في تصحيح أو تحديث معلوماتك</div>
                            </li>
                            <li>
                                <i data-lucide="trash-2" style="width: 18px; height: 18px; color: #9f7aea;"></i>
                                <div>الحق في حذف بياناتك الشخصية</div>
                            </li>
                            <li>
                                <i data-lucide="download" style="width: 18px; height: 18px; color: #9f7aea;"></i>
                                <div>الحق في الحصول على نسخة من بياناتك</div>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Cookies Section -->
                <div class="privacy-card">
                    <div class="privacy-icon">
                        <i data-lucide="cookie" style="width: 24px; height: 24px;"></i>
                    </div>
                    <h3 class="privacy-section-title">
                        <i data-lucide="globe" style="width: 20px; height: 20px; color: #667eea;"></i>
                        ملفات تعريف الارتباط
                    </h3>
                    <div class="privacy-content">
                        <p class="mb-4">نستخدم ملفات تعريف الارتباط لتحسين تجربتك وتخصيص المحتوى:</p>
                        <ul class="privacy-list">
                            <li>
                                <i data-lucide="zap" style="width: 18px; height: 18px; color: #38b2ac;"></i>
                                <div>ملفات ضرورية لعمل الموقع الأساسي</div>
                            </li>
                            <li>
                                <i data-lucide="bar-chart" style="width: 18px; height: 18px; color: #38b2ac;"></i>
                                <div>ملفات تحليلية لفهم سلوك المستخدمين</div>
                            </li>
                            <li>
                                <i data-lucide="heart" style="width: 18px; height: 18px; color: #38b2ac;"></i>
                                <div>ملفات تفضيلات لحفظ اختياراتك</div>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Contact CTA -->
                <div class="contact-cta">
                    <h4 class="mb-3">
                        <i data-lucide="help-circle" style="width: 24px; height: 24px; margin-left: 10px;"></i>
                        هل لديك أسئلة حول سياسة الخصوصية؟
                    </h4>
                    <p class="mb-4">فريقنا جاهز للإجابة على جميع استفساراتك وتوضيح أي نقطة تحتاج لمزيد من التفاصيل</p>
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="mailto:hodifaabdhalmoaz@gmail.com" class="btn-modern">
                            <i data-lucide="mail" style="width: 18px; height: 18px;"></i>
                            راسلنا عبر البريد
                        </a>
                        <a href="https://wa.me/967777548421" class="btn-modern" target="_blank">
                            <i data-lucide="message-circle" style="width: 18px; height: 18px;"></i>
                            تواصل عبر واتساب
                        </a>
                    </div>
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
    const cards = document.querySelectorAll('.privacy-card');
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
