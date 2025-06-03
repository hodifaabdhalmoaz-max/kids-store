<footer class="site-footer bg-dark text-white pt-5 pb-3">
    <div class="container">
        <div class="row">
            <!-- About -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="text-uppercase mb-4">{{ __('messages.footer_about') }}</h5>
                <p>{{ __('messages.footer_about_description') }}</p>
                <div class="mt-4">
                    <h6>{{ __('messages.footer_follow_us') }}</h6>
                    <div class="social-links">
                        <a href="https://www.facebook.com/share/1E3T83a8KD/" target="_blank" class="text-white me-2"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://x.com/moaz_abdh" target="_blank" class="text-white me-2"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.instagram.com/invites/contact/?utm_source=ig_contact_invite&utm_medium=copy_link&utm_content=mwfgwqx" target="_blank" class="text-white me-2"><i class="fab fa-instagram"></i></a>
                        <a href="https://wa.me/967718706242" target="_blank" class="text-white me-2"><i class="fab fa-whatsapp"></i></a>
                    <a href="https://www.linkedin.com/in/hodifa-al-hodify-30644b289" target="_blank" class="text-white me-2"><i class="fab fa-linkedin"></i></a>
                    <a href="https://github.com/HA1234098765" target="_blank" class="text-white"><i class="fab fa-github"></i></a>
                    </div>
                </div>
            </div>

            <!-- Categories -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="text-uppercase mb-4">{{ __('messages.footer_categories') }}</h5>
                <ul class="list-unstyled">
                    @foreach($categories ?? [] as $category)
                    <li class="mb-2"><a href="{{ route('shop.category', $category->slug) }}" class="text-white text-decoration-none">{{ $category->name }}</a></li>
                    @endforeach
                </ul>
            </div>

            <!-- Information -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="text-uppercase mb-4">{{ __('messages.footer_information') }}</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ route('about') }}" class="text-white text-decoration-none">{{ __('messages.footer_about_us') }}</a></li>
                    <li class="mb-2"><a href="{{ route('contact') }}" class="text-white text-decoration-none">{{ __('messages.footer_contact') }}</a></li>
                    <li class="mb-2"><a href="{{ route('terms') }}" class="text-white text-decoration-none">{{ __('messages.footer_terms') }}</a></li>
                    <li class="mb-2"><a href="{{ route('privacy') }}" class="text-white text-decoration-none">{{ __('messages.footer_privacy') }}</a></li>
                    <li class="mb-2"><a href="{{ route('returns') }}" class="text-white text-decoration-none">{{ __('messages.footer_returns') }}</a></li>
                    <li class="mb-2"><a href="{{ route('shipping') }}" class="text-white text-decoration-none">{{ __('messages.footer_shipping') }}</a></li>
                    <li class="mb-2"><a href="{{ route('faq') }}" class="text-white text-decoration-none">{{ __('messages.footer_faq') }}</a></li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="text-uppercase mb-4">{{ __('messages.footer_newsletter') }}</h5>
                <p>{{ __('messages.footer_newsletter_description') }}</p>
                <form action="{{ route('newsletter.subscribe') }}" method="POST" class="mt-3">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control" placeholder="{{ __('messages.newsletter_email_placeholder') }}" required>
                        <button class="btn btn-primary" type="submit">{{ __('messages.newsletter_subscribe_button') }}</button>
                    </div>
                </form>
            </div>
        </div>

        <hr class="my-4">

        <!-- Bottom Footer -->
        <div class="row align-items-center">
            <div class="col-md-6 mb-3 mb-md-0">
                <p class="mb-0">{{ __('messages.footer_copyright') }} &copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('messages.all_rights_reserved') }}</p>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="payment-methods">
                    <span class="me-2">{{ __('messages.footer_payment_methods') }}:</span>
                    <img src="{{ asset('assets/images/payments/visa.png') }}" alt="Visa" class="me-2" height="30">
                    <img src="{{ asset('assets/images/payments/mastercard.png') }}" alt="MasterCard" class="me-2" height="30">
                    <img src="{{ asset('assets/images/payments/mada.png') }}" alt="Mada" class="me-2" height="30">
                    <img src="{{ asset('assets/images/payments/apple-pay.png') }}" alt="Apple Pay" class="me-2" height="30">
                    <img src="{{ asset('assets/images/payments/stcpay.png') }}" alt="STC Pay" height="30">
                </div>
            </div>
        </div>
    </div>
</footer>
