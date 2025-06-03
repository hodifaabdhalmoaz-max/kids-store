@extends('layouts.base')

@section('title', __('messages.about'))

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home.index') }}">{{ __('messages.home') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('messages.about') }}</li>
        </ol>
    </nav>
    
    <!-- About Us Header -->
    <div class="row mb-5">
        <div class="col-lg-6 mb-4 mb-lg-0">
            <img src="{{ asset('assets/images/about-us.jpg') }}" alt="{{ __('messages.about') }}" class="img-fluid rounded shadow-sm">
        </div>
        <div class="col-lg-6">
            <h1 class="display-5 mb-4">{{ __('messages.about_us_title') }}</h1>
            <p class="lead">{{ __('messages.about_us_subtitle') }}</p>
            <p>{{ __('messages.about_us_description') }}</p>
            <div class="d-flex mt-4">
                <a href="{{ route('shop.index') }}" class="btn btn-primary me-3">
                    <i class="fas fa-shopping-cart me-2"></i>{{ __('messages.shop_now') }}
                </a>
                <a href="{{ route('contact') }}" class="btn btn-outline-primary">
                    <i class="fas fa-envelope me-2"></i>{{ __('messages.contact_us') }}
                </a>
            </div>
        </div>
    </div>
    
    <!-- Our Story -->
    <div class="card border-0 shadow-sm mb-5">
        <div class="card-body p-4 p-lg-5">
            <h2 class="mb-4">{{ __('messages.our_story') }}</h2>
            <p>{{ __('messages.our_story_description_1') }}</p>
            <p>{{ __('messages.our_story_description_2') }}</p>
            <p class="mb-0">{{ __('messages.our_story_description_3') }}</p>
        </div>
    </div>
    
    <!-- Our Mission & Vision -->
    <div class="row mb-5">
        <div class="col-md-6 mb-4 mb-md-0">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <span class="display-5 text-primary">
                            <i class="fas fa-bullseye"></i>
                        </span>
                    </div>
                    <h3 class="text-center mb-3">{{ __('messages.our_mission') }}</h3>
                    <p class="mb-0">{{ __('messages.our_mission_description') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <span class="display-5 text-primary">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                    <h3 class="text-center mb-3">{{ __('messages.our_vision') }}</h3>
                    <p class="mb-0">{{ __('messages.our_vision_description') }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Our Values -->
    <div class="card border-0 shadow-sm mb-5">
        <div class="card-body p-4 p-lg-5">
            <h2 class="text-center mb-5">{{ __('messages.our_values') }}</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="text-center">
                        <span class="display-6 text-primary mb-3 d-block">
                            <i class="fas fa-heart"></i>
                        </span>
                        <h4>{{ __('messages.value_quality') }}</h4>
                        <p class="mb-0">{{ __('messages.value_quality_description') }}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <span class="display-6 text-primary mb-3 d-block">
                            <i class="fas fa-shield-alt"></i>
                        </span>
                        <h4>{{ __('messages.value_safety') }}</h4>
                        <p class="mb-0">{{ __('messages.value_safety_description') }}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <span class="display-6 text-primary mb-3 d-block">
                            <i class="fas fa-leaf"></i>
                        </span>
                        <h4>{{ __('messages.value_sustainability') }}</h4>
                        <p class="mb-0">{{ __('messages.value_sustainability_description') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Meet Our Team -->
    <h2 class="text-center mb-5">{{ __('messages.meet_our_team') }}</h2>
    <div class="row g-4 mb-5">
        <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <img src="{{ asset('assets/images/team/team-1.jpg') }}" class="card-img-top" alt="Team Member">
                <div class="card-body text-center">
                    <h4 class="card-title">حذيفة عبدالمعز الحذيفي</h4>
                    <p class="text-muted">{{ __('messages.team_founder_ceo') }}</p>
                    <p class="card-text">{{ __('messages.team_member_description') }}</p>
                    <div class="social-links mt-3">
                        <a href="https://www.facebook.com/share/1E3T83a8KD/" target="_blank" class="btn btn-sm btn-outline-primary me-1"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://x.com/moaz_abdh" target="_blank" class="btn btn-sm btn-outline-primary me-1"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.linkedin.com/in/hodifa-al-hodify-30644b289" target="_blank" class="btn btn-sm btn-outline-primary me-1"><i class="fab fa-linkedin"></i></a>
                        <a href="mailto:hodifaabdhalmoaz@gmail.com" class="btn btn-sm btn-outline-primary"><i class="fas fa-envelope"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <img src="{{ asset('assets/images/team/team-2.jpg') }}" class="card-img-top" alt="Team Member">
                <div class="card-body text-center">
                    <h4 class="card-title">{{ __('messages.team_member_name_2') }}</h4>
                    <p class="text-muted">{{ __('messages.team_product_manager') }}</p>
                    <p class="card-text">{{ __('messages.team_member_description') }}</p>
                    <div class="social-links mt-3">
                        <a href="#" class="btn btn-sm btn-outline-primary me-1"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="btn btn-sm btn-outline-primary me-1"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="btn btn-sm btn-outline-primary me-1"><i class="fab fa-linkedin"></i></a>
                        <a href="#" class="btn btn-sm btn-outline-primary"><i class="fas fa-envelope"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <img src="{{ asset('assets/images/team/team-3.jpg') }}" class="card-img-top" alt="Team Member">
                <div class="card-body text-center">
                    <h4 class="card-title">{{ __('messages.team_member_name_3') }}</h4>
                    <p class="text-muted">{{ __('messages.team_customer_service') }}</p>
                    <p class="card-text">{{ __('messages.team_member_description') }}</p>
                    <div class="social-links mt-3">
                        <a href="#" class="btn btn-sm btn-outline-primary me-1"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="btn btn-sm btn-outline-primary me-1"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="btn btn-sm btn-outline-primary me-1"><i class="fab fa-linkedin"></i></a>
                        <a href="#" class="btn btn-sm btn-outline-primary"><i class="fas fa-envelope"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Contact Us CTA -->
    <div class="card border-0 shadow-sm bg-primary text-white">
        <div class="card-body p-4 p-lg-5 text-center">
            <h2 class="mb-3">{{ __('messages.have_questions') }}</h2>
            <p class="lead mb-4">{{ __('messages.contact_us_description') }}</p>
            <div class="d-flex justify-content-center">
                <a href="{{ route('contact') }}" class="btn btn-light btn-lg me-3">
                    <i class="fas fa-envelope me-2"></i>{{ __('messages.contact_us') }}
                </a>
                <a href="https://wa.me/967718706242" target="_blank" class="btn btn-success btn-lg">
                    <i class="fab fa-whatsapp me-2"></i>{{ __('messages.whatsapp_us') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
