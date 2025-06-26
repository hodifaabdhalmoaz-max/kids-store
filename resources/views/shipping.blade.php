@extends('layouts.app')

@section('content')
<main>
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home.index') }}">{{ __('messages.home') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('messages.footer_shipping') }}</li>
        </ol>
    </nav>

    <!-- Shipping Header -->
    <div class="row mb-5">
        <div class="col-12">
            <h1 class="display-5 mb-4 text-center">{{ __('messages.footer_shipping') }}</h1>
            <p class="lead text-center text-muted">{{ __('messages.shipping_subtitle') }}</p>
        </div>
    </div>

    <!-- Shipping Content -->
    <div class="row">
        <div class="col-lg-10 mx-auto">

            <!-- Shipping Areas -->
            <div class="row mb-5">
                <div class="col-md-6 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="text-center mb-3">
                                <i class="fas fa-city fa-3x text-primary"></i>
                            </div>
                            <h3 class="text-center mb-3">{{ __('messages.shipping_local_title') }}</h3>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>{{ __('messages.shipping_local_point_1') }}</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>{{ __('messages.shipping_local_point_2') }}</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>{{ __('messages.shipping_local_point_3') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="text-center mb-3">
                                <i class="fas fa-truck fa-3x text-success"></i>
                            </div>
                            <h3 class="text-center mb-3">{{ __('messages.shipping_national_title') }}</h3>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>{{ __('messages.shipping_national_point_1') }}</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>{{ __('messages.shipping_national_point_2') }}</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>{{ __('messages.shipping_national_point_3') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping Process -->
            <div class="card border-0 shadow-sm mb-5">
                <div class="card-body p-4 p-lg-5">
                    <h2 class="h4 mb-4 text-center">{{ __('messages.shipping_process_title') }}</h2>
                    <div class="row">
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="text-center">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                                    <i class="fas fa-shopping-cart fa-lg"></i>
                                </div>
                                <h5>{{ __('messages.shipping_step_1_title') }}</h5>
                                <p class="text-muted">{{ __('messages.shipping_step_1_content') }}</p>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="text-center">
                                <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                                    <i class="fas fa-check-circle fa-lg"></i>
                                </div>
                                <h5>{{ __('messages.shipping_step_2_title') }}</h5>
                                <p class="text-muted">{{ __('messages.shipping_step_2_content') }}</p>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="text-center">
                                <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                                    <i class="fas fa-box fa-lg"></i>
                                </div>
                                <h5>{{ __('messages.shipping_step_3_title') }}</h5>
                                <p class="text-muted">{{ __('messages.shipping_step_3_content') }}</p>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="text-center">
                                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                                    <i class="fas fa-home fa-lg"></i>
                                </div>
                                <h5>{{ __('messages.shipping_step_4_title') }}</h5>
                                <p class="text-muted">{{ __('messages.shipping_step_4_content') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping Rates -->
            <div class="card border-0 shadow-sm mb-5">
                <div class="card-body p-4 p-lg-5">
                    <h2 class="h4 mb-4 text-center">{{ __('messages.shipping_rates_title') }}</h2>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>{{ __('messages.shipping_location') }}</th>
                                    <th>{{ __('messages.shipping_time') }}</th>
                                    <th>{{ __('messages.shipping_cost') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ __('messages.shipping_sanaa') }}</td>
                                    <td>{{ __('messages.shipping_1_2_days') }}</td>
                                    <td>{{ __('messages.shipping_free') }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('messages.shipping_aden') }}</td>
                                    <td>{{ __('messages.shipping_2_3_days') }}</td>
                                    <td>{{ __('messages.shipping_1000_rial') }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('messages.shipping_taiz') }}</td>
                                    <td>{{ __('messages.shipping_2_3_days') }}</td>
                                    <td>{{ __('messages.shipping_1000_rial') }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('messages.shipping_hodeidah') }}</td>
                                    <td>{{ __('messages.shipping_3_4_days') }}</td>
                                    <td>{{ __('messages.shipping_1500_rial') }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('messages.shipping_other_cities') }}</td>
                                    <td>{{ __('messages.shipping_3_5_days') }}</td>
                                    <td>{{ __('messages.shipping_contact_us') }}</td>
                                </tr>
                            </tbody>
                        </table>
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
                    <h3 class="mb-3">{{ __('messages.shipping_questions') }}</h3>
                    <p class="mb-4">{{ __('messages.shipping_questions_text') }}</p>
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
