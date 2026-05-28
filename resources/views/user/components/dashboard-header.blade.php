@push('styles')
<style>
    .dashboard-hero {
        background: linear-gradient(135deg, #f0c14b 0%, #d4a853 100%);
        color: white;
        padding: 140px 0 80px;
        position: relative;
        overflow: hidden;
        margin-top: -90px;
        margin-bottom: 40px;
    }

    .dashboard-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dashboard-pattern" width="40" height="40" patternUnits="userSpaceOnUse"><circle cx="20" cy="20" r="2" fill="white" opacity="0.15"/><circle cx="10" cy="10" r="1" fill="white" opacity="0.08"/><circle cx="30" cy="30" r="1" fill="white" opacity="0.08"/></pattern></defs><rect width="100" height="100" fill="url(%23dashboard-pattern)"/></svg>');
        pointer-events: none;
    }

    .breadcrumb-dashboard {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(15px);
        border-radius: 50px;
        padding: 12px 25px;
        margin-bottom: 30px;
        display: inline-flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .breadcrumb-dashboard a {
        color: #d4a853;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .breadcrumb-dashboard a:hover {
        color: #b58b3a;
    }

    .dashboard-hero-title {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 10px;
        color: white;
        text-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    @media (max-width: 768px) {
        .dashboard-hero {
            padding: 120px 0 60px;
            margin-top: -90px;
            margin-bottom: 30px;
        }
        .dashboard-hero-title {
            font-size: 2rem;
        }
    }

    /* Header Contrast Fix for Dashboard Pages */
    body:has(.dashboard-hero) #header {
        background: rgba(255, 255, 255, 0.05) !important;
        backdrop-filter: blur(5px);
    }

    body:has(.dashboard-hero) #header .navigation__link,
    body:has(.dashboard-hero) #header .header-tools__item {
        color: white !important;
        text-shadow: 0 1px 2px rgba(0,0,0,0.2);
    }
</style>
@endpush

<section class="dashboard-hero">
    <div class="container position-relative">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <div class="breadcrumb-dashboard">
                    <a href="{{ route('home.index') }}">
                        <i class="bi bi-house"></i>
                        الرئيسية
                    </a>
                    
                    @if(!isset($isDashboard) || !$isDashboard)
                    <i class="bi bi-chevron-left" style="color: #a0aec0; font-size: 12px;"></i>
                    <a href="{{ route('user.index') }}">لوحة التحكم</a>
                    @endif
                    
                    <i class="bi bi-chevron-left" style="color: #a0aec0; font-size: 12px;"></i>
                    <span style="color: #1a202c; font-weight: 700;">{{ $title }}</span>
                </div>

                <h1 class="dashboard-hero-title">{{ $title }}</h1>
                @if(isset($description))
                <p class="lead mb-0 text-white-50">{{ $description }}</p>
                @endif
            </div>
        </div>
    </div>
</section>
