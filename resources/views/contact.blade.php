@extends('layouts.app')

@section('title', 'اتصل بنا - متجر الأطفال')
@section('description', 'تواصل معنا في متجر الأطفال - نحن هنا لخدمتكم والإجابة على جميع استفساراتكم')
@section('meta_description', 'تواصل معنا في متجر الأطفال - نحن هنا لخدمتكم والإجابة على جميع استفساراتكم')

@push('styles')
<style>
.contact-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 100px 0;
    position: relative;
    overflow: hidden;
}

.contact-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="contact-pattern" width="30" height="30" patternUnits="userSpaceOnUse"><circle cx="15" cy="15" r="2" fill="white" opacity="0.1"/><circle cx="5" cy="5" r="1" fill="white" opacity="0.05"/><circle cx="25" cy="25" r="1" fill="white" opacity="0.05"/></pattern></defs><rect width="100" height="100" fill="url(%23contact-pattern)"/></svg>');
}

.contact-card {
    background: white;
    border-radius: 25px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.08);
    padding: 40px;
    margin-bottom: 30px;
    border: 1px solid #f0f4f8;
    transition: all 0.4s ease;
    position: relative;
    overflow: hidden;
}

.contact-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 5px;
    height: 100%;
    background: linear-gradient(to bottom, #667eea, #764ba2);
    border-radius: 0 25px 25px 0;
}

.contact-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 30px 60px rgba(0,0,0,0.15);
}

.contact-icon {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 25px;
    color: white;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

.contact-info-item {
    background: white;
    border-radius: 15px;
    padding: 30px;
    margin-bottom: 25px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    border: 1px solid #f0f4f8;
    text-align: center;
}

.contact-info-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

.contact-info-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    color: white;
}

.form-group {
    margin-bottom: 25px;
}

.form-control {
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 15px 20px;
    font-size: 16px;
    transition: all 0.3s ease;
    background: #f8fafc;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    background: white;
}

.form-label {
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 8px;
    display: block;
}

.btn-contact {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border: none;
    padding: 15px 40px;
    border-radius: 50px;
    font-weight: 600;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 12px;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
    width: 100%;
    justify-content: center;
}

.btn-contact:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
    color: white;
}

.breadcrumb-contact {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(15px);
    border-radius: 50px;
    padding: 15px 30px;
    margin-bottom: 50px;
    display: inline-flex;
    align-items: center;
    gap: 12px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    border: 1px solid rgba(255,255,255,0.2);
}

.breadcrumb-contact a {
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s ease;
}

.breadcrumb-contact a:hover {
    color: #764ba2;
}

.quick-contact {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    padding: 40px;
    text-align: center;
    color: white;
    margin: 40px 0;
    position: relative;
    overflow: hidden;
}

.quick-contact::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: pulse 6s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); opacity: 0.5; }
    50% { transform: scale(1.05); opacity: 0.8; }
}

.btn-quick {
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
    margin: 8px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.btn-quick:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    color: #764ba2;
}

.working-hours {
    background: #f8fafc;
    border-radius: 15px;
    padding: 25px;
    margin-top: 20px;
}

.working-hours h6 {
    color: #2d3748;
    font-weight: 700;
    margin-bottom: 15px;
}

.working-hours .day {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #e2e8f0;
}

.working-hours .day:last-child {
    border-bottom: none;
}

@media (max-width: 768px) {
    .contact-hero {
        padding: 80px 0;
    }

    .contact-card {
        padding: 25px;
        margin-bottom: 20px;
    }

    .contact-icon {
        width: 60px;
        height: 60px;
    }

    .contact-info-item {
        padding: 20px;
    }
}
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="contact-hero">
    <div class="container position-relative">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <div class="breadcrumb-contact">
                    <a href="{{ route('home.index') }}">
                        <i data-lucide="home" style="width: 18px; height: 18px;"></i>
                        الرئيسية
                    </a>
                    <i data-lucide="chevron-left" style="width: 16px; height: 16px; color: #a0aec0;"></i>
                    <span style="color: #1a202c; font-weight: 700;">اتصل بنا</span>
                </div>

                <h1 class="display-3 fw-bold mb-4">تواصل معنا</h1>
                <p class="lead mb-0">نحن هنا لخدمتكم والإجابة على جميع استفساراتكم</p>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<main class="py-5">
    <div class="container">
        <div class="row">
            <!-- Contact Information -->
            <div class="col-lg-4 mb-4">
                <!-- Address -->
                <div class="contact-info-item">
                    <div class="contact-info-icon">
                        <i data-lucide="map-pin" style="width: 24px; height: 24px;"></i>
                    </div>
                    <h5 class="mb-3">العنوان</h5>
                    <p class="text-muted mb-0">صنعاء، الجمهورية اليمنية</p>
                </div>

                <!-- Phone -->
                <div class="contact-info-item">
                    <div class="contact-info-icon">
                        <i data-lucide="phone" style="width: 24px; height: 24px;"></i>
                    </div>
                    <h5 class="mb-3">الهاتف</h5>
                    <p class="text-muted mb-2">+967 777548421</p>
                    <p class="text-muted mb-0">+967 718706242</p>
                </div>

                <!-- Email -->
                <div class="contact-info-item">
                    <div class="contact-info-icon">
                        <i data-lucide="mail" style="width: 24px; height: 24px;"></i>
                    </div>
                    <h5 class="mb-3">البريد الإلكتروني</h5>
                    <p class="text-muted mb-0">hodifaabdhalmoaz@gmail.com</p>
                </div>

                <!-- Working Hours -->
                <div class="working-hours">
                    <h6>
                        <i data-lucide="clock" style="width: 18px; height: 18px; margin-left: 8px;"></i>
                        ساعات العمل
                    </h6>
                    <div class="day">
                        <span>السبت - الخميس</span>
                        <span>9:00 ص - 8:00 م</span>
                    </div>
                    <div class="day">
                        <span>الجمعة</span>
                        <span>2:00 م - 8:00 م</span>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="col-lg-8">
                <div class="contact-card">
                    <div class="contact-icon">
                        <i data-lucide="message-square" style="width: 28px; height: 28px;"></i>
                    </div>
                    <h3 class="mb-4" style="color: #1a202c; font-weight: 700;">أرسل لنا رسالة</h3>

                    <form action="#" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label">
                                        <i data-lucide="user" style="width: 16px; height: 16px; margin-left: 5px;"></i>
                                        الاسم الكامل
                                    </label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="أدخل اسمك الكامل" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="form-label">
                                        <i data-lucide="mail" style="width: 16px; height: 16px; margin-left: 5px;"></i>
                                        البريد الإلكتروني
                                    </label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="example@email.com" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone" class="form-label">
                                        <i data-lucide="phone" style="width: 16px; height: 16px; margin-left: 5px;"></i>
                                        رقم الهاتف
                                    </label>
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="+967 777548421">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="subject" class="form-label">
                                        <i data-lucide="tag" style="width: 16px; height: 16px; margin-left: 5px;"></i>
                                        الموضوع
                                    </label>
                                    <select class="form-control" id="subject" name="subject" required>
                                        <option value="">اختر الموضوع</option>
                                        <option value="استفسار عام">استفسار عام</option>
                                        <option value="استفسار عن منتج">استفسار عن منتج</option>
                                        <option value="شكوى">شكوى</option>
                                        <option value="اقتراح">اقتراح</option>
                                        <option value="طلب شراكة">طلب شراكة</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="message" class="form-label">
                                <i data-lucide="message-circle" style="width: 16px; height: 16px; margin-left: 5px;"></i>
                                الرسالة
                            </label>
                            <textarea class="form-control" id="message" name="message" rows="6" placeholder="اكتب رسالتك هنا..." required></textarea>
                        </div>
                        <button type="submit" class="btn-contact">
                            <i data-lucide="send" style="width: 18px; height: 18px;"></i>
                            إرسال الرسالة
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Quick Contact Section -->
        <div class="quick-contact">
            <h4 class="mb-3">
                <i data-lucide="zap" style="width: 24px; height: 24px; margin-left: 10px;"></i>
                تحتاج مساعدة سريعة؟
            </h4>
            <p class="mb-4">لا تتردد في التواصل معنا مباشرة عبر أي من الطرق التالية</p>
            <div class="d-flex flex-wrap justify-content-center gap-3">
                <a href="tel:+967777548421" class="btn-quick">
                    <i data-lucide="phone" style="width: 18px; height: 18px;"></i>
                    اتصل الآن
                </a>
                <a href="https://wa.me/967777548421" target="_blank" class="btn-quick">
                    <i data-lucide="message-circle" style="width: 18px; height: 18px;"></i>
                    واتساب
                </a>
                <a href="mailto:hodifaabdhalmoaz@gmail.com" class="btn-quick">
                    <i data-lucide="mail" style="width: 18px; height: 18px;"></i>
                    البريد الإلكتروني
                </a>
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
    const cards = document.querySelectorAll('.contact-card, .contact-info-item');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    });

    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
        observer.observe(card);
    });

    // Form validation and enhancement
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Simple form validation
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const message = document.getElementById('message').value.trim();

            if (!name || !email || !message) {
                alert('يرجى ملء جميع الحقول المطلوبة');
                return;
            }

            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert('يرجى إدخال بريد إلكتروني صحيح');
                return;
            }

            // Show success message (in real implementation, this would submit to server)
            alert('شكراً لك! تم إرسال رسالتك بنجاح. سنتواصل معك قريباً.');
            form.reset();
        });
    }
});
</script>
@endpush
