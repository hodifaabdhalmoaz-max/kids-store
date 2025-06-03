<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- SEO Meta Tags -->
    <title>@yield('title', $seoMetadata['title'] ?? config('app.name'))</title>
    <meta name="description" content="@yield('meta_description', $seoMetadata['metaDescription'] ?? '')">
    <meta name="keywords" content="@yield('meta_keywords', $seoMetadata['metaKeywords'] ?? '')">
    <meta name="author" content="{{ config('app.name') }}">
    <link rel="canonical" href="{{ $seoMetadata['canonical'] ?? url()->current() }}">
    <meta name="robots" content="{{ $seoMetadata['robots'] ?? 'index, follow' }}">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="@yield('title', $seoMetadata['ogTitle'] ?? config('app.name'))">
    <meta property="og:description" content="@yield('meta_description', $seoMetadata['ogDescription'] ?? '')">
    <meta property="og:image" content="{{ $seoMetadata['ogImage'] ?? asset('assets/images/logo.png') }}">
    <meta property="og:url" content="{{ $seoMetadata['ogUrl'] ?? url()->current() }}">
    <meta property="og:type" content="{{ $seoMetadata['ogType'] ?? 'website' }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    
    <!-- Twitter Meta Tags -->
    <meta name="twitter:card" content="{{ $seoMetadata['twitterCard'] ?? 'summary_large_image' }}">
    <meta name="twitter:title" content="@yield('title', $seoMetadata['twitterTitle'] ?? config('app.name'))">
    <meta name="twitter:description" content="@yield('meta_description', $seoMetadata['twitterDescription'] ?? '')">
    <meta name="twitter:image" content="{{ $seoMetadata['twitterImage'] ?? asset('assets/images/logo.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    @if(app()->getLocale() == 'ar')
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.rtl.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.rtl.css') }}">
    @endif
    
    @stack('styles')
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}">
</head>
<body class="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <!-- Header -->
    @include('layouts.partials.header')
    
    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>
    
    <!-- Footer -->
    @include('layouts.partials.footer')
    
    <!-- Scripts -->
    <script src="{{ asset('assets/js/main.js') }}"></script>
    @stack('scripts')
    
    @yield('scripts')
</body>
</html>
