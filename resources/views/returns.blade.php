@extends('layouts.app')

@section('content')
<main>
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home.index') }}">{{ __('messages.home') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('messages.footer_returns') }}</li>
        </ol>
    </nav>

    <!-- Returns Header -->
    <div class="row mb-5">
        <div class="col-12">
            <h1 class="display-5 mb-4 text-center">{{ __('messages.footer_returns') }}</h1>
            <p class="lead text-center text-muted">{{ __('messages.returns_subtitle') }}</p>
        </div>
    </div>

    <!-- Returns Content -->
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-lg-5">

                    <!-- Return Policy -->
                    <div class="mb-5">
                        <h2 class="h4 mb-3">{{ __('messages.return_policy_title') }}</h2>
                        <p>{{ __('messages.return_policy_content') }}</p>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>{{ __('messages.return_policy_point_1') }}</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>{{ __('messages.return_policy_point_2') }}</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>{{ __('messages.return_policy_point_3') }}</li>
                        </ul>
                    </div>

                    <!-- Return Process -->
                    <div class="mb-5">
                        <h2 class="h4 mb-3">{{ __('messages.return_process_title') }}</h2>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <span class="fw-bold">1</span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5>{{ __('messages.return_step_1_title') }}</h5>
                                        <p class="text-muted">{{ __('messages.return_step_1_content') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <span class="fw-bold">2</span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5>{{ __('messages.return_step_2_title') }}</h5>
                                        <p class="text-muted">{{ __('messages.return_step_2_content') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <span class="fw-bold">3</span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5>{{ __('messages.return_step_3_title') }}</h5>
                                        <p class="text-muted">{{ __('messages.return_step_3_content') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <span class="fw-bold">4</span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5>{{ __('messages.return_step_4_title') }}</h5>
                                        <p class="text-muted">{{ __('messages.return_step_4_content') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact for Returns -->
                    <div class="mb-0">
                        <h2 class="h4 mb-3">{{ __('messages.return_contact_title') }}</h2>
                        <p>{{ __('messages.return_contact_content') }}</p>
                        <div class="contact-info">
                            <p><strong>{{ __('messages.email') }}:</strong> <a href="mailto:hodifaabdhalmoaz@gmail.com">hodifaabdhalmoaz@gmail.com</a></p>
                            <p><strong>{{ __('messages.phone') }}:</strong> <a href="tel:+967777548421">+967 777548421</a></p>
                            <p><strong>{{ __('messages.whatsapp') }}:</strong> <a href="https://wa.me/967777548421" target="_blank">+967 777548421</a></p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Contact CTA -->
    <div class="row mt-5">
        <div class="col-lg-8 mx-auto">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body p-4 text-center">
                    <h3 class="mb-3">{{ __('messages.need_help_returns') }}</h3>
                    <p class="mb-4">{{ __('messages.returns_help_text') }}</p>
                    <div class="d-flex justify-content-center">
                        <a href="{{ route('contact') }}" class="btn btn-light btn-lg me-3">
                            <i class="fas fa-envelope me-2"></i>{{ __('messages.contact_us') }}
                        </a>
                        <a href="https://wa.me/967777548421" target="_blank" class="btn btn-success btn-lg">
                            <i class="fab fa-whatsapp me-2"></i>{{ __('messages.whatsapp_us') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</main>
@endsection
