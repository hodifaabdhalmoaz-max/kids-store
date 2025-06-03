@extends('layouts.base')

@section('title', __('messages.contact_us'))

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home.index') }}">{{ __('messages.home') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('messages.contact_us') }}</li>
        </ol>
    </nav>
    
    <div class="row">
        <!-- Contact Information -->
        <div class="col-lg-4 mb-4 mb-lg-0">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h3 class="mb-4">{{ __('messages.contact_info') }}</h3>
                    
                    <div class="mb-4">
                        <h5><i class="fas fa-map-marker-alt text-primary me-2"></i> {{ __('messages.contact_address') }}</h5>
                        <p class="mb-0">صنعاء، اليمن</p>
                    </div>
                    
                    <div class="mb-4">
                        <h5><i class="fas fa-phone-alt text-primary me-2"></i> {{ __('messages.contact_phone_number') }}</h5>
                        <p class="mb-1"><a href="tel:+967777548421" class="text-dark">+967 777548421</a></p>
                        <p class="mb-0"><a href="tel:+967718706242" class="text-dark">+967 718706242</a></p>
                    </div>
                    
                    <div class="mb-4">
                        <h5><i class="fas fa-envelope text-primary me-2"></i> {{ __('messages.contact_email_address') }}</h5>
                        <p class="mb-0"><a href="mailto:hodifaabdhalmoaz@gmail.com" class="text-dark">hodifaabdhalmoaz@gmail.com</a></p>
                    </div>
                    
                    <div class="mb-4">
                        <h5><i class="fas fa-clock text-primary me-2"></i> {{ __('messages.contact_working_hours') }}</h5>
                        <p class="mb-1">السبت - الخميس: 9:00 صباحًا - 8:00 مساءً</p>
                        <p class="mb-0">الجمعة: 2:00 مساءً - 8:00 مساءً</p>
                    </div>
                    
                    <div>
                        <h5><i class="fas fa-share-alt text-primary me-2"></i> {{ __('messages.contact_social_media') }}</h5>
                        <div class="social-links mt-2">
                            <a href="https://www.facebook.com/share/1E3T83a8KD/" target="_blank" class="btn btn-outline-primary me-2"><i class="fab fa-facebook-f"></i></a>
                            <a href="https://x.com/moaz_abdh" target="_blank" class="btn btn-outline-primary me-2"><i class="fab fa-twitter"></i></a>
                            <a href="https://www.instagram.com/invites/contact/?utm_source=ig_contact_invite&utm_medium=copy_link&utm_content=mwfgwqx" target="_blank" class="btn btn-outline-primary me-2"><i class="fab fa-instagram"></i></a>
                            <a href="https://wa.me/967718706242" target="_blank" class="btn btn-outline-primary me-2"><i class="fab fa-whatsapp"></i></a>
                            <a href="https://www.linkedin.com/in/hodifa-al-hodify-30644b289" target="_blank" class="btn btn-outline-primary me-2"><i class="fab fa-linkedin"></i></a>
                            <a href="https://github.com/HA1234098765" target="_blank" class="btn btn-outline-primary"><i class="fab fa-github"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Contact Form -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h3 class="mb-4">{{ __('messages.contact_form') }}</h3>
                    
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    
                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    
                    <form action="{{ route('contact.send') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">{{ __('messages.contact_name') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">{{ __('messages.contact_email') }} <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">{{ __('messages.contact_phone') }}</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}">
                                @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="subject" class="form-label">{{ __('messages.contact_subject') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" value="{{ old('subject') }}" required>
                                @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="message" class="form-label">{{ __('messages.contact_message') }} <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
                            @error('message')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input @error('privacy') is-invalid @enderror" type="checkbox" id="privacy" name="privacy" required>
                                <label class="form-check-label" for="privacy">
                                    {{ __('messages.agree') }} <a href="{{ route('privacy') }}" target="_blank">{{ __('messages.privacy') }}</a>
                                </label>
                                @error('privacy')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>{{ __('messages.contact_send') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Map -->
    <div class="mt-5">
        <h3 class="mb-4">{{ __('messages.our_location') }}</h3>
        <div class="ratio ratio-21x9">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15333.084909285506!2d44.19958!3d15.35937!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1603dbdcc30d6c8d%3A0x7ae35e746a3c98f!2sSana&#39;a%2C%20Yemen!5e0!3m2!1sen!2s!4v1623825289123!5m2!1sen!2s" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </div>
    
    <!-- WhatsApp Business Section -->
    <div class="mt-5">
        <div class="card border-0 shadow-sm bg-light">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3 class="text-primary mb-3">{{ __('messages.contact_us_on_whatsapp') }}</h3>
                        <p class="mb-md-0">{{ __('messages.whatsapp_business_description') }}</p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <a href="https://wa.me/967718706242" target="_blank" class="btn btn-success btn-lg">
                            <i class="fab fa-whatsapp me-2"></i>{{ __('messages.contact_on_whatsapp') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
