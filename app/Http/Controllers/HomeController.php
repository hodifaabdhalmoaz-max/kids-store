<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function index()
    {
        // جلب الفئات والعلامات التجارية والمنتجات للصفحة الرئيسية
        $categories = Category::orderBy('name', 'ASC')->take(8)->get();
        $brands = Brand::orderBy('name', 'ASC')->take(6)->get();
        $featured_products = Product::where('featured', true)->take(8)->get();
        $latest_products = Product::orderBy('created_at', 'DESC')->take(8)->get();

        return view('index', compact('categories', 'brands', 'featured_products', 'latest_products'));
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
            'privacy' => 'required|accepted',
        ], [
            'name.required' => 'الاسم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'subject.required' => 'الموضوع مطلوب',
            'message.required' => 'الرسالة مطلوبة',
            'privacy.required' => 'يجب الموافقة على سياسة الخصوصية',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // يمكنك إضافة إرسال الإيميل هنا لاحقاً
            // Mail::to('hodifaabdhalmoaz@gmail.com')->send(new ContactMail($request->all()));

            return redirect()->back()->with('success', 'تم إرسال رسالتك بنجاح! سنتواصل معك قريباً.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إرسال الرسالة. يرجى المحاولة مرة أخرى.');
        }
    }

    public function about()
    {
        return view('about');
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
