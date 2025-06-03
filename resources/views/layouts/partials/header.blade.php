<header class="site-header">
    <!-- Top Bar -->
    <div class="top-bar bg-primary text-white py-2">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="top-bar-contact d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-phone-alt me-1"></i> <a href="tel:+967777548421" class="text-white">+967 777548421</a>
                        </div>
                        <div>
                            <i class="fas fa-envelope me-1"></i> <a href="mailto:hodifaabdhalmoaz@gmail.com" class="text-white">hodifaabdhalmoaz@gmail.com</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-end align-items-center">
                        <!-- Language Switcher -->
                        <div class="dropdown me-3">
                            <a class="text-white dropdown-toggle" href="#" role="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ app()->getLocale() == 'ar' ? 'العربية' : 'English' }}
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="languageDropdown">
                                <li><a class="dropdown-item" href="{{ route('language.switch', 'ar') }}">العربية</a></li>
                                <li><a class="dropdown-item" href="{{ route('language.switch', 'en') }}">English</a></li>
                            </ul>
                        </div>

                        <!-- Social Media Links -->
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
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <div class="main-header py-3">
        <div class="container">
            <div class="row align-items-center">
                <!-- Logo -->
                <div class="col-lg-3 col-md-4 col-6">
                    <div class="logo">
                        <a href="{{ route('home.index') }}">
                            <img src="{{ asset('assets/images/logo.png') }}" alt="{{ config('app.name') }}" class="img-fluid">
                        </a>
                    </div>
                </div>

                <!-- Search Bar -->
                <div class="col-lg-5 col-md-4 d-none d-md-block">
                    <form action="{{ route('shop.search') }}" method="GET" class="search-form">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="{{ __('messages.search_placeholder') }}" value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> {{ __('messages.search') }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Header Actions -->
                <div class="col-lg-4 col-md-4 col-6">
                    <div class="header-actions d-flex justify-content-end align-items-center">
                        <!-- Mobile Search Toggle -->
                        <div class="d-md-none me-3">
                            <button class="btn btn-link text-dark p-0" type="button" data-bs-toggle="collapse" data-bs-target="#mobileSearch" aria-expanded="false">
                                <i class="fas fa-search fa-lg"></i>
                            </button>
                        </div>

                        <!-- Wishlist -->
                        <div class="me-3">
                            <a href="{{ route('user.wishlist') }}" class="text-dark position-relative">
                                <i class="fas fa-heart fa-lg"></i>
                                @auth
                                    @if(auth()->user()->wishlist->count() > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        {{ auth()->user()->wishlist->count() }}
                                    </span>
                                    @endif
                                @endauth
                            </a>
                        </div>

                        <!-- Cart -->
                        <div class="me-3">
                            <a href="{{ route('cart.index') }}" class="text-dark position-relative">
                                <i class="fas fa-shopping-cart fa-lg"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ Cart::instance('cart')->count() }}
                                </span>
                            </a>
                        </div>

                        <!-- User Account -->
                        <div class="dropdown">
                            <a class="text-dark dropdown-toggle" href="#" role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user fa-lg"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                @auth
                                    <li><a class="dropdown-item" href="{{ route('user.dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                                    <li><a class="dropdown-item" href="{{ route('user.orders') }}">{{ __('messages.user_orders') }}</a></li>
                                    <li><a class="dropdown-item" href="{{ route('user.wishlist') }}">{{ __('messages.user_wishlist') }}</a></li>
                                    <li><a class="dropdown-item" href="{{ route('user.profile') }}">{{ __('messages.user_profile') }}</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    @if(auth()->user()->utype === 'ADM')
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">{{ __('messages.admin') }}</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    @endif
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item">{{ __('messages.logout') }}</button>
                                        </form>
                                    </li>
                                @else
                                    <li><a class="dropdown-item" href="{{ route('login') }}">{{ __('messages.login') }}</a></li>
                                    <li><a class="dropdown-item" href="{{ route('register') }}">{{ __('messages.register') }}</a></li>
                                @endauth
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Search Collapse -->
            <div class="collapse mt-3 d-md-none" id="mobileSearch">
                <form action="{{ route('shop.search') }}" method="GET" class="search-form">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="{{ __('messages.search_placeholder') }}" value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home.index') ? 'active' : '' }}" href="{{ route('home.index') }}">{{ __('messages.home') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('shop.index') ? 'active' : '' }}" href="{{ route('shop.index') }}">{{ __('messages.shop') }}</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="categoriesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ __('messages.product_category') }}
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="categoriesDropdown">
                            @foreach($categories ?? [] as $category)
                            <li><a class="dropdown-item" href="{{ route('shop.category', $category->slug) }}">{{ $category->name }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="brandsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ __('messages.product_brand') }}
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="brandsDropdown">
                            @foreach($brands ?? [] as $brand)
                            <li><a class="dropdown-item" href="{{ route('shop.brand', $brand->slug) }}">{{ $brand->name }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">{{ __('messages.about') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">{{ __('messages.contact') }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
