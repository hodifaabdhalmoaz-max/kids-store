<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function index()
    {
        // Cache homepage data for 10 minutes — most visited page
        $slides = Cache::remember('home_slides', 600, function () {
            return \App\Models\Slide::active()->ordered()->get();
        });

        $categories = Cache::remember('home_categories', 600, function () {
            return Category::select('id', 'name', 'slug', 'image')
                ->orderBy('name', 'ASC')
                ->take(8)
                ->get();
        });

        $brands = Cache::remember('home_brands', 600, function () {
            return Brand::select('id', 'name', 'slug', 'image')
                ->orderBy('name', 'ASC')
                ->take(6)
                ->get();
        });

        $featured_products = Cache::remember('home_featured_products', 600, function () {
            return Product::where('featured', true)
                ->select(['id', 'name', 'slug', 'short_description', 'regular_price', 'sale_price', 'image', 'images', 'category_id', 'brand_id', 'featured', 'quantity'])
                ->with(['colors'])
                ->withCount(['reviews as active_reviews_count' => function($query) {
                    $query->where('status', true);
                }])
                ->withAvg(['reviews as active_reviews_avg' => function($query) {
                    $query->where('status', true);
                }], 'rating')
                ->take(8)->get();
        });

        $latest_products = Cache::remember('home_latest_products', 600, function () {
            return Product::orderBy('created_at', 'DESC')
                ->select(['id', 'name', 'slug', 'short_description', 'regular_price', 'sale_price', 'image', 'images', 'category_id', 'brand_id', 'featured', 'quantity'])
                ->with(['colors'])
                ->withCount(['reviews as active_reviews_count' => function($query) {
                    $query->where('status', true);
                }])
                ->withAvg(['reviews as active_reviews_avg' => function($query) {
                    $query->where('status', true);
                }], 'rating')
                ->take(8)->get();
        });

        $offer_products = Cache::remember('home_offer_products', 600, function () {
            return Product::where('is_offer', 1)
                ->orderBy('created_at', 'DESC')
                ->select(['id', 'name', 'slug', 'short_description', 'regular_price', 'sale_price', 'image', 'images', 'category_id', 'brand_id', 'featured', 'quantity'])
                ->with(['colors'])
                ->withCount(['reviews as active_reviews_count' => function($query) {
                    $query->where('status', true);
                }])
                ->withAvg(['reviews as active_reviews_avg' => function($query) {
                    $query->where('status', true);
                }], 'rating')
                ->take(8)->get();
        });

        return view('index', compact('categories', 'brands', 'featured_products', 'latest_products', 'offer_products', 'slides'));
    }

    public function contact()
    {
        return view('contact');
    }

    public function contact_send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ], [
            'name.required' => 'الاسم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'subject.required' => 'الموضوع مطلوب',
            'message.required' => 'الرسالة مطلوبة',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // إرسال الإيميل
            Mail::to('hodifaabdhalmoaz@gmail.com')->send(new ContactMail($request->all()));

            return redirect()->back()->with('success', 'تم إرسال رسالتك بنجاح! سنتواصل معك قريباً.');
        } catch (\Exception $e) {
            \Log::error('Contact Form Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء إرسال الرسالة. يرجى المحاولة مرة أخرى.');
        }
    }

    public function about()
    {
        $productCount = Cache::remember('about_product_count', 3600, function () {
            return Product::count();
        });
        $customerCount = Cache::remember('about_customer_count', 3600, function () {
            return \App\Models\User::count();
        });

        return view('about', compact('productCount', 'customerCount'));
    }

    public function privacy()
    {
        return view('privacy');
    }

    public function terms()
    {
        return view('terms');
    }

    public function returns()
    {
        return view('returns');
    }

    public function shipping()
    {
        return view('shipping');
    }

    public function faq()
    {
        return view('faq');
    }

    public function newsletter_subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'يرجى إدخال بريد إلكتروني صحيح.');
        }

        try {
            // Here you would typically save to database or send to email service
            // For now, just return success message
            return redirect()->back()->with('success', 'تم الاشتراك في النشرة الإخبارية بنجاح!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء الاشتراك. يرجى المحاولة مرة أخرى.');
        }
    }
}
