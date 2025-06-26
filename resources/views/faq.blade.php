@extends('layouts.app')

@section('content')
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="shop-checkout container">
        <h2 class="page-title">الأسئلة الشائعة</h2>

        <!-- Breadcrumb -->
        <div class="breadcrumb mb-4 d-none d-md-block">
            <a href="{{ route('home.index') }}" class="menu-link menu-link_us-s text-uppercase fw-medium">الرئيسية</a>
            <span class="breadcrumb-separator menu-link fw-medium ps-1 pe-1">/</span>
            <span class="menu-link menu-link_us-s text-uppercase fw-medium">الأسئلة الشائعة</span>
        </div>

        <!-- FAQ Content -->
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="faq-content">
                    <p class="lead text-center mb-5 text-secondary">إجابات سريعة لأهم الاستفسارات حول منتجاتنا وخدماتنا</p>

                    <!-- FAQ Accordion -->
                    <div class="accordion" id="faqAccordion">

                        <!-- FAQ 1 -->
                        <div class="accordion-item mb-3">
                            <h2 class="accordion-header" id="faq1">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                                    كيف يمكنني تقديم طلب؟
                                </button>
                            </h2>
                            <div id="collapse1" class="accordion-collapse collapse show" aria-labelledby="faq1" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    يمكنك تقديم طلب بسهولة من خلال تصفح منتجاتنا، إضافة المنتجات المرغوبة إلى السلة، ثم إكمال عملية الدفع. نوفر عدة طرق دفع آمنة ومريحة.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 2 -->
                        <div class="accordion-item mb-3">
                            <h2 class="accordion-header" id="faq2">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                                    ما هي مدة التوصيل؟
                                </button>
                            </h2>
                            <div id="collapse2" class="accordion-collapse collapse" aria-labelledby="faq2" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    نوفر خدمة توصيل سريعة خلال 1-3 أيام عمل داخل المدن الرئيسية، و3-5 أيام للمناطق الأخرى. نرسل لك رسالة تأكيد مع رقم التتبع فور شحن طلبك.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 3 -->
                        <div class="accordion-item mb-3">
                            <h2 class="accordion-header" id="faq3">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
                                    ما هي طرق الدفع المتاحة؟
                                </button>
                            </h2>
                            <div id="collapse3" class="accordion-collapse collapse" aria-labelledby="faq3" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    نقبل الدفع عند الاستلام، التحويل البنكي، والدفع الإلكتروني. جميع المعاملات آمنة ومحمية بأحدث تقنيات التشفير.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 4 -->
                        <div class="accordion-item mb-3">
                            <h2 class="accordion-header" id="faq4">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4" aria-expanded="false" aria-controls="collapse4">
                                    هل يمكنني إرجاع المنتج؟
                                </button>
                            </h2>
                            <div id="collapse4" class="accordion-collapse collapse" aria-labelledby="faq4" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    نعم، يمكنك إرجاع المنتج خلال 14 يوماً من تاريخ الاستلام بشرط أن يكون في حالته الأصلية. تطبق شروط وأحكام الإرجاع حسب نوع المنتج.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 5 -->
                        <div class="accordion-item mb-3">
                            <h2 class="accordion-header" id="faq5">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse5" aria-expanded="false" aria-controls="collapse5">
                                    هل المنتجات آمنة للأطفال؟
                                </button>
                            </h2>
                            <div id="collapse5" class="accordion-collapse collapse" aria-labelledby="faq5" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    جميع منتجاتنا تخضع لاختبارات صارمة وتلتزم بمعايير السلامة الدولية. نختار منتجات من علامات تجارية موثوقة ومعتمدة لضمان سلامة أطفالكم.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 6 -->
                        <div class="accordion-item mb-3">
                            <h2 class="accordion-header" id="faq6">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse6" aria-expanded="false" aria-controls="collapse6">
                                    كيف يمكنني التواصل مع خدمة العملاء؟
                                </button>
                            </h2>
                            <div id="collapse6" class="accordion-collapse collapse" aria-labelledby="faq6" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    يمكنك التواصل معنا عبر الهاتف، البريد الإلكتروني، أو واتساب. فريق خدمة العملاء متاح من السبت إلى الخميس من 9 صباحاً إلى 10 مساءً.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Still Have Questions -->
        <div class="row mt-5">
            <div class="col-lg-8 mx-auto">
                <div class="text-center p-4 bg-light rounded">
                    <h4 class="mb-3">لم تجد إجابة لسؤالك؟</h4>
                    <p class="mb-4 text-secondary">لا تتردد في التواصل معنا! فريق خدمة العملاء جاهز لمساعدتك</p>
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <a href="{{ route('contact') }}" class="btn btn-primary">
                            <i class="fas fa-envelope me-2"></i>تواصل معنا
                        </a>
                        <a href="https://wa.me/967777548421" target="_blank" class="btn btn-success">
                            <i class="fab fa-whatsapp me-2"></i>واتساب
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
