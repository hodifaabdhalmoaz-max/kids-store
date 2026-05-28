@extends('layouts.app')
@section('content')
<main>
  <!-- Hero Section matching About Us -->
  <section class="category-hero" style="background: linear-gradient(135deg, #f0c14b 0%, #d4a853 100%); padding: 100px 0; color: white; text-align: center; position: relative; overflow: hidden;">
    <!-- Pattern Background -->
    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: url('data:image/svg+xml,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 100 100\'><defs><pattern id=\'contact-pattern\' width=\'30\' height=\'30\' patternUnits=\'userSpaceOnUse\'><circle cx=\'15\' cy=\'15\' r=\'2\' fill=\'white\' opacity=\'0.1\'/><circle cx=\'5\' cy=\'5\' r=\'1\' fill=\'white\' opacity=\'0.05\'/><circle cx=\'25\' cy=\'25\' r=\'1\' fill=\'white\' opacity=\'0.05\'/></pattern></defs><rect width=\'100\' height=\'100\' fill=\'url(%23contact-pattern)\'/></svg>'); z-index: 0;"></div>
    
    <div class="container position-relative" style="z-index: 1;">
      <div class="breadcrumb-dashboard mx-auto mb-3">
        <a href="{{ route('home.index') }}">
          <i class="bi bi-house"></i>
          الرئيسية
        </a>
        <i class="bi bi-chevron-left" style="color: #a0aec0; font-size: 12px;"></i>
        <span style="color: #1a202c; font-weight: 700;">جميع الفئات</span>
      </div>
      <h1 class="display-5 fw-bold mb-4">جميع الفئات</h1>
      
      <!-- Search Bar -->
      <div class="search-container mx-auto" style="max-width: 600px;">
        <form action="{{ route('shop.search') }}" method="GET" class="position-relative">
          <input type="text" name="search" class="form-control rounded-pill px-4 py-3 border-0 shadow-sm" placeholder="ابحث الآن في منتجات المتجر" style="background-color: white;">
          <button type="submit" class="btn position-absolute top-50 translate-middle-y text-muted" style="left: 10px; right: auto; border: none; background: transparent;">
            <i class="bi bi-search" style="font-size: 1.2rem;"></i>
          </button>
        </form>
      </div>
    </div>
  </section>

  <div class="mb-4 pb-4"></div>
  <section class="category-page container">
    <h2 class="d-none">الفئات</h2>

    <!-- Categories Grid -->
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3 g-md-4">
      @foreach($categories as $category)
      <div class="col">
        <a href="{{ route('shop.category', $category->slug) }}" class="text-decoration-none">
          <div class="category-card card h-100 border-0 shadow-sm text-center py-4 px-2" style="border-radius: 12px; transition: all 0.3s ease;">
            <div class="category-image-wrapper mb-3 mx-auto d-flex align-items-center justify-content-center" style="height: 80px;">
              @php
                $imageSrc = $category->image;
                if (!empty($imageSrc)) {
                    if (\Illuminate\Support\Str::startsWith($imageSrc, 'categories/')) {
                        $imageSrc = asset('uploads/' . $imageSrc);
                    } else {
                        $imageSrc = asset('uploads/categories/' . $imageSrc);
                    }
                } else {
                    $imageSrc = asset('assets/images/home/demo3/category_1.png');
                }
              @endphp
              <img src="{{ $imageSrc }}?v={{ time() }}" alt="{{ $category->name }}" class="img-fluid" style="max-height: 100%; max-width: 100%; object-fit: contain;" onerror="this.src='{{ asset('assets/images/home/demo3/category_1.png') }}'">
            </div>
            <h6 class="category-title mb-0 fw-bold text-dark" style="font-size: 0.9rem;">{{ $category->name }}</h6>
          </div>
        </a>
      </div>
      @endforeach
    </div>
  </section>
</main>

<style>
  .category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    border-color: var(--gold-start) !important;
  }
  .category-title {
    color: #333;
    transition: color 0.3s ease;
  }
  .category-card:hover .category-title {
    color: var(--gold-start);
  }
</style>
@endsection
