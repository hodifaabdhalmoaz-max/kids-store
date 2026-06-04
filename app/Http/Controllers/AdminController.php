<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Order;
use App\Models\Color;
use App\Models\Size;
use App\Models\Slide;
use App\Services\RevenueAnalyticsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Laravel\Facades\Image;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        // تحديد الفترة الزمنية للفرز مع التحقق من صحة البيانات
        $allowedPeriods = ['this_week', 'last_week', 'this_month', 'last_month', 'this_year', 'last_year'];
        $period = $request->get('period', 'this_week');

        // التحقق من أن الفترة المطلوبة مسموحة
        if (!in_array($period, $allowedPeriods)) {
            $period = 'this_week';
        }

        $dateRange = $this->getDateRange($period);

        // إحصائيات الطلبات
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'ordered')->count();
        $deliveredOrders = Order::where('status', 'delivered')->count();
        $cancelledOrders = Order::where('status', 'canceled')->count();

        // إحصائيات المبالغ
        $totalAmount = Order::sum('total');
        $pendingAmount = Order::where('status', 'ordered')->sum('total');
        $deliveredAmount = Order::where('status', 'delivered')->sum('total');
        $cancelledAmount = Order::where('status', 'canceled')->sum('total');

        // استخدام الخدمة الجديدة للحصول على بيانات الإيرادات
        $revenueService = new RevenueAnalyticsService();
        $analytics = $revenueService->getRevenueAnalytics($period);

        // إعداد البيانات للعرض
        $revenueData = [
            'current_revenue' => $analytics['current']['revenue'] > 0 ? $analytics['current']['revenue'] : $totalAmount,
            'current_orders' => $analytics['current']['total_orders'] > 0 ? $analytics['current']['total_orders'] : $totalAmount,
            'revenue_change' => $analytics['changes']['revenue'],
            'orders_change' => $analytics['changes']['orders'],
            'total_orders_count' => $totalOrders,
            'delivered_orders_count' => $deliveredOrders,
            'total_amount' => $totalAmount
        ];

        $chartData = $analytics['chart_data'];

        // الطلبات الحديثة مع بيانات المستخدم
        $recentOrders = Order::with(['user', 'orderItems'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.index', compact(
            'totalOrders',
            'pendingOrders',
            'deliveredOrders',
            'cancelledOrders',
            'totalAmount',
            'pendingAmount',
            'deliveredAmount',
            'cancelledAmount',
            'recentOrders',
            'revenueData',
            'chartData',
            'period'
        ));
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }

    //Brand
    public function brands()
    {
        $brands = Brand::withCount('products')->orderBy('id', 'DESC')->paginate(10);
        return view('admin.brands', compact('brands'));
    }

    public function add_brand()
    {
        return view('admin.brand-add');
    }

    public function brand_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug',
            'image' => 'mimes:png,jpg,jpeg|max:2048'
        ]);

        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = $request->slug;
        $image = $request->file('image');
        $file_name = Str::uuid().'.webp';
        $this->GenerateBrandThumbailsImage($image, $file_name);
        $brand->image = $file_name;
        $brand->save();

        // مسح الكاش لضمان ظهور الصور الجديدة في الواجهة الأمامية
        if (function_exists('opcache_reset')) {
            opcache_reset();
        }

        return redirect()->route('admin.brands')->with('status', 'Brand has added succesfully!');
    }

    public function brand_edit($id)
    {
        $brand = Brand::findOrFail($id);
        return view('admin.brand-edit', compact('brand'));
    }

    public function brand_update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,'.$request->id,
            'image' => 'mimes:png,jpg,jpeg|max:2048'
        ]);

        $brand = Brand::findOrFail($request->id);
        $brand->name = $request->name;
        $brand->slug = $request->slug;
        if ($request->hasFile('image')) {
            if (File::exists(public_path('uploads/brands').'/'.$brand->image)) {
                File::delete(public_path('uploads/brands').'/'.$brand->image);
            }
            $image = $request->file('image');
            $file_name = Str::uuid().'.webp';
            $this->GenerateBrandThumbailsImage($image, $file_name);
            $brand->image = $file_name;
        }
        $brand->save();

        // مسح الكاش لضمان تحديث الصور في الواجهة الأمامية
        if (function_exists('opcache_reset')) {
            opcache_reset();
        }

        return redirect()->route('admin.brands')->with('status', 'Brand has updated succesfully!');
    }

    public function GenerateBrandThumbailsImage($image, $imageName)
    {
        $destinationPath = public_path('uploads/brands');
        $img = Image::read($image->path());
        $img->cover(124, 124, "top");
        $img->resize(124, 124, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$imageName);
    }

    public function brand_delete($id)
    {
        $brand = Brand::findOrFail($id);
        if (File::exists(public_path('uploads/brands').'/'.$brand->image)) {
            File::delete(public_path('uploads/brands').'/'.$brand->image);
        }
        $brand->delete();
        return redirect()->route('admin.brands')->with('status', 'Brand has deleted succesfully!');
    }
    //End Brand

    //Category
    public function categories()
    {
        $categories = Category::withCount('products')->orderBy('id', 'DESC')->paginate(10);
        return view('admin.categories', compact('categories'));
    }

    public function category_add()
    {
        return view('admin.category-add');
    }

    public function category_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug',
            'image' => 'mimes:png,jpg,jpeg|max:2048'
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->slug = $request->slug;
        $image = $request->file('image');
        $file_name = Str::uuid().'.webp';
        $this->GenerateCategoryThumbailsImage($image, $file_name);
        $category->image = $file_name;
        $category->save();

        // مسح الكاش لضمان ظهور الصور الجديدة في الواجهة الأمامية
        if (function_exists('opcache_reset')) {
            opcache_reset();
        }

        return redirect()->route('admin.categories')->with('status', 'Category has added succesfully!');
    }

    public function category_edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.category-edit', compact('category'));
    }

    public function category_update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,'.$request->id,
            'image' => 'mimes:png,jpg,jpeg|max:2048'
        ]);

        $category = Category::findOrFail($request->id);
        $category->name = $request->name;
        $category->slug = $request->slug;
        if ($request->hasFile('image')) {
            if (File::exists(public_path('uploads/categories').'/'.$category->image)) {
                File::delete(public_path('uploads/categories').'/'.$category->image);
            }
            $image = $request->file('image');
            $file_name = Str::uuid().'.webp';
            $this->GenerateCategoryThumbailsImage($image, $file_name);
            $category->image = $file_name;
        }
        $category->save();

        // مسح الكاش لضمان تحديث الصور في الواجهة الأمامية
        if (function_exists('opcache_reset')) {
            opcache_reset();
        }

        return redirect()->route('admin.categories')->with('status', 'Category has updated succesfully!');
    }

    public function GenerateCategoryThumbailsImage($image, $imageName)
    {
        $destinationPath = public_path('uploads/categories');
        $img = Image::read($image->path());
        $img->cover(124, 124, "top");
        $img->resize(124, 124, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$imageName);
    }

    public function category_delete($id)
    {
        $category = Category::findOrFail($id);
        if (File::exists(public_path('uploads/categories').'/'.$category->image)) {
            File::delete(public_path('uploads/categories').'/'.$category->image);
        }
        $category->delete();
        return redirect()->route('admin.categories')->with('status', 'Category has deleted succesfully!');
    }
    //End Category

    //Product
    public function products()
    {
        $products = Product::with(['category', 'brand'])->orderBy('created_at', 'DESC')->paginate(10);
        return view('admin.products', compact('products'));
    }

    public function product_add()
    {
        $categories = Category::select('id', 'name')->orderBy('name')->get();
        $brands = Brand::select('id', 'name')->orderBy('name')->get();
        $colors = Color::active()->ordered()->get();
        $sizes = Size::active()->ordered()->get();
        return view('admin.product-add', compact('categories', 'brands', 'colors', 'sizes'));
    }

    public function product_store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:products,slug',
            'short_description' => 'required|string',
            'description' => 'required|string',
            'regular_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'SKU' => 'required|string|unique:products,SKU',
            'stock_status' => 'required|in:instock,outofstock',
            'featured' => 'required|boolean',
            'is_offer' => 'required|boolean',
            'quantity' => 'required|integer|min:0',
            'image' => 'required|mimes:png,jpg,jpeg,webp|max:2048',
            'category_id' => 'required|integer|exists:categories,id',
            'brand_id' => 'required|integer|exists:brands,id',
            'colors' => 'nullable|array',
            'colors.*' => 'integer|exists:colors,id',
            'sizes' => 'nullable|array',
            'sizes.*' => 'integer|exists:sizes,id',
            'dimensions' => 'nullable|string',
            'weight' => 'nullable|string'
        ], [
            'category_id.required' => 'يجب اختيار فئة للمنتج',
            'category_id.integer' => 'يجب اختيار فئة صحيحة',
            'category_id.exists' => 'الفئة المختارة غير موجودة',
            'brand_id.required' => 'يجب اختيار علامة تجارية للمنتج',
            'brand_id.integer' => 'يجب اختيار علامة تجارية صحيحة',
            'brand_id.exists' => 'العلامة التجارية المختارة غير موجودة',
            'name.required' => 'اسم المنتج مطلوب',
            'SKU.unique' => 'رمز المنتج موجود مسبقاً',
            'regular_price.numeric' => 'السعر يجب أن يكون رقماً',
            'sale_price.numeric' => 'سعر التخفيض يجب أن يكون رقماً',
            'quantity.integer' => 'الكمية يجب أن تكون رقماً صحيحاً'
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->slug = $request->slug;
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->is_offer = $request->is_offer;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $product->dimensions = $request->dimensions;
        $product->weight = $request->weight;

        $current_timestamp = (string) Str::uuid();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $current_timestamp.'.webp';
            $this->GenerateProductThumbailImage($image, $imageName);
            $product->image = $imageName;
        }

        $gallery_arr = array();
        $gallery_images = "";
        $counter = 1;

        if ($request->hasFile('images')) {
            $allowedfileExtion = ['jpg', 'png', 'jpeg'];
            $files = $request->file('images');
            foreach ($files as $file)
            {
                $gextension = $file->getClientOriginalExtension();
                $gcheck = in_array($gextension, $allowedfileExtion);
                if ($gcheck)
                {
                    $gfileName = $current_timestamp."-".$counter.".webp";
                    $this->GenerateProductThumbailImage($file, $gfileName);
                    array_push($gallery_arr, $gfileName);
                    $counter = $counter + 1;
                }
            }
            $gallery_images = implode(',', $gallery_arr);
        }

        $product->images = $gallery_images;
        $product->save();

        if ($request->has('colors')) {
            $product->colors()->sync($request->colors);
            $colorNames = Color::whereIn('id', $request->colors)->pluck('name')->toArray();
            $product->color = implode(', ', $colorNames);
        } else {
            $product->color = null;
        }

        if ($request->has('sizes')) {
            $product->sizes()->sync($request->sizes);
            $sizeNames = Size::whereIn('id', $request->sizes)->pluck('name')->toArray();
            $product->size = implode(', ', $sizeNames);
        } else {
            $product->size = null;
        }
        $product->save();

        // Clear filter caching and homepage caching
        Cache::forget('search_filters');
        Cache::forget('home_featured_products');
        Cache::forget('home_latest_products');
        Cache::forget('home_offer_products');

        return redirect()->route('admin.products')->with('status', 'تم إضافة المنتج بنجاح!');
    }

    public function product_edit($id)
    {
        $product = Product::with(['colors', 'sizes'])->findOrFail($id);
        $categories = Category::select('id', 'name')->orderBy('name')->get();
        $brands = Brand::select('id', 'name')->orderBy('name')->get();
        $colors = Color::active()->ordered()->get();
        $sizes = Size::active()->ordered()->get();
        return view('admin.product-edit', compact('product', 'categories', 'brands', 'colors', 'sizes'));
    }

    public function product_update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:products,slug,'.$request->id,
            'short_description' => 'required|string',
            'description' => 'required|string',
            'regular_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'SKU' => 'required|string|unique:products,SKU,'.$request->id,
            'stock_status' => 'required|in:instock,outofstock',
            'featured' => 'required|boolean',
            'is_offer' => 'required|boolean',
            'quantity' => 'required|integer|min:0',
            'image' => 'nullable|mimes:png,jpg,jpeg|max:2048',
            'category_id' => 'required|integer|exists:categories,id',
            'brand_id' => 'required|integer|exists:brands,id',
            'colors' => 'nullable|array',
            'colors.*' => 'integer|exists:colors,id',
            'sizes' => 'nullable|array',
            'sizes.*' => 'integer|exists:sizes,id',
            'dimensions' => 'nullable|string',
            'weight' => 'nullable|string'
        ], [
            'category_id.required' => 'يجب اختيار فئة للمنتج',
            'category_id.integer' => 'يجب اختيار فئة صحيحة',
            'category_id.exists' => 'الفئة المختارة غير موجودة',
            'brand_id.required' => 'يجب اختيار علامة تجارية للمنتج',
            'brand_id.integer' => 'يجب اختيار علامة تجارية صحيحة',
            'brand_id.exists' => 'العلامة التجارية المختارة غير موجودة',
            'name.required' => 'اسم المنتج مطلوب',
            'SKU.unique' => 'رمز المنتج موجود مسبقاً',
            'regular_price.numeric' => 'السعر يجب أن يكون رقماً',
            'sale_price.numeric' => 'سعر التخفيض يجب أن يكون رقماً',
            'quantity.integer' => 'الكمية يجب أن تكون رقماً صحيحاً'
        ]);

        $product = Product::findOrFail($request->id);
        $product->name = $request->name;
        $product->slug = $request->slug;
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->is_offer = $request->is_offer;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $product->dimensions = $request->dimensions;
        $product->weight = $request->weight;

        $current_timestamp = (string) Str::uuid();


        if ($request->hasFile('image')) {
            if (File::exists(public_path('uploads/products').'/'.$product->image)) {
                File::delete(public_path('uploads/products').'/'.$product->image);
            }

            if (File::exists(public_path('uploads/products/thumbnails').'/'.$product->image)) {
                File::delete(public_path('uploads/products/thumbnails').'/'.$product->image);
            }
            $image = $request->file('image');
            $imageName = $current_timestamp.'.webp';
            $this->GenerateProductThumbailImage($image, $imageName);
            $product->image = $imageName;
        }

        $gallery_arr = array();
        $gallery_images = "";
        $counter = 1;

        if ($request->hasFile('images')) {
            foreach (explode(',', $product->images) as $ofile) {
                if (File::exists(public_path('uploads/products').'/'.$ofile)) {
                    File::delete(public_path('uploads/products').'/'.$ofile);
                }

                if (File::exists(public_path('uploads/products/thumbnails').'/'.$ofile)) {
                    File::delete(public_path('uploads/products/thumbnails').'/'.$ofile);
                }
            }

            $allowedfileExtion = ['jpg', 'png', 'jpeg'];
            $files = $request->file('images');
            foreach ($files as $file) {
                $gextension = $file->getClientOriginalExtension();
                $gcheck = in_array($gextension, $allowedfileExtion);
                if ($gcheck) {
                    $gfileName = $current_timestamp."-".$counter.".webp";
                    $this->GenerateProductThumbailImage($file, $gfileName);
                    array_push($gallery_arr, $gfileName);
                    $counter = $counter + 1;
                }
            }
            $gallery_images = implode(',', $gallery_arr);
            $product->images = $gallery_images;
        }

        $product->save();

        if ($request->has('colors')) {
            $product->colors()->sync($request->colors);
            $colorNames = Color::whereIn('id', $request->colors)->pluck('name')->toArray();
            $product->color = implode(', ', $colorNames);
        } else {
            $product->colors()->detach();
            $product->color = null;
        }

        if ($request->has('sizes')) {
            $product->sizes()->sync($request->sizes);
            $sizeNames = Size::whereIn('id', $request->sizes)->pluck('name')->toArray();
            $product->size = implode(', ', $sizeNames);
        } else {
            $product->sizes()->detach();
            $product->size = null;
        }
        $product->save();

        // Clear filter caching and homepage caching
        Cache::forget('search_filters');
        Cache::forget('home_featured_products');
        Cache::forget('home_latest_products');
        Cache::forget('home_offer_products');

        return redirect()->route('admin.products')->with('status', 'تم تحديث المنتج بنجاح!');
    }

    public function GenerateProductThumbailImage($image, $imageName)
    {
        $destinationPathThumbail = public_path('uploads/products/thumbnails');
        $destinationPath = public_path('uploads/products');
        $img = Image::read($image->path());

        $img->cover(540, 689, "top");
        $img->resize(540, 689, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$imageName);

        $img->resize(104, 104, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPathThumbail.'/'.$imageName);
    }

    public function product_delete($id)
    {
        $product = Product::findOrFail($id);
        if (File::exists(public_path('uploads/products').'/'.$product->image)) {
            File::delete(public_path('uploads/products').'/'.$product->image);
        }

        if (File::exists(public_path('uploads/products/thumbnails').'/'.$product->image)) {
            File::delete(public_path('uploads/products/thumbnails').'/'.$product->image);
        }

        foreach (explode(',', $product->images) as $ofile) {
            if (File::exists(public_path('uploads/products').'/'.$ofile)) {
                File::delete(public_path('uploads/products').'/'.$ofile);
            }

            if (File::exists(public_path('uploads/products/thumbnails').'/'.$ofile)) {
                File::delete(public_path('uploads/products/thumbnails').'/'.$ofile);
            }
        }
        $product->delete();
        return redirect()->route('admin.products')->with('status', 'product has deleted succesfully!');
    }
    //End Product
    public function coupons()
    {
        $coupons = Coupon::orderBy('expiry_date','DESC')->paginate(12);
        return view('admin.coupons', compact('coupons'));
    }
    public function coupon_add()
    {

        return view('admin.coupon-add');
    }
    public function coupon_store(Request $request)
    {
        $request->validate([
            'code'=>'required',
            'type'=>'required',
            'value'=>'required|numeric',
            'cart_value'=>'required|numeric',
            'expiry_date'=>'required|date',

        ]);
        $coupon = new Coupon();
        $coupon->code = $request->code;
        $coupon->type = $request->type;
        $coupon->value = $request->value;
        $coupon->cart_value = $request->cart_value;
        $coupon->expiry_date = $request->expiry_date;
        $coupon ->save();
        return redirect()->route('admin.coupons')->with('status', 'coupon has been added succesfully!');

    }
    public function coupon_edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('admin.coupon-edit', compact('coupon'));
    }
    public function coupon_update(Request $request)
    {
        $request->validate([
            'code'=>'required',
            'type'=>'required',
            'value'=>'required|numeric',
            'cart_value'=>'required|numeric',
            'expiry_date'=>'required|date',
        ]);
        $coupon = Coupon::findOrFail($request->id);
        $coupon->code = $request->code;
        $coupon->type = $request->type;
        $coupon->value = $request->value;
        $coupon->cart_value = $request->cart_value;
        $coupon->expiry_date = $request->expiry_date;
        $coupon ->save();
        return redirect()->route('admin.coupons')->with('status', 'coupon has been updated succesfully!');
    }
    public function coupon_delete($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();
        return redirect()->route('admin.coupons')->with('status', 'coupon has been deleted succesfully!');
    }

    /**
     * تحديد النطاق الزمني بناءً على الفترة المحددة
     */
    private function getDateRange($period)
    {
        $now = Carbon::now();

        switch ($period) {
            case 'this_week':
                return [
                    'start' => $now->copy()->startOfWeek(),
                    'end' => $now->copy()->endOfWeek()
                ];
            case 'last_week':
                return [
                    'start' => $now->copy()->subWeek()->startOfWeek(),
                    'end' => $now->copy()->subWeek()->endOfWeek()
                ];
            case 'this_month':
                return [
                    'start' => $now->copy()->startOfMonth(),
                    'end' => $now->copy()->endOfMonth()
                ];
            case 'last_month':
                return [
                    'start' => $now->copy()->subMonth()->startOfMonth(),
                    'end' => $now->copy()->subMonth()->endOfMonth()
                ];
            case 'this_year':
                return [
                    'start' => $now->copy()->startOfYear(),
                    'end' => $now->copy()->endOfYear()
                ];
            case 'last_year':
                return [
                    'start' => $now->copy()->subYear()->startOfYear(),
                    'end' => $now->copy()->subYear()->endOfYear()
                ];
            default:
                return [
                    'start' => $now->copy()->startOfWeek(),
                    'end' => $now->copy()->endOfWeek()
                ];
        }
    }

    /**
     * حساب بيانات الإيرادات والأرباح
     */
    private function calculateRevenueData($dateRange)
    {
        try {
            // إجمالي الإيرادات للفترة (الطلبات المسلمة فقط)
            $currentRevenue = Order::where('status', 'delivered')
                ->whereNotNull('delivered_date')
                ->whereBetween('delivered_date', [$dateRange['start'], $dateRange['end']])
                ->sum('total') ?? 0;

            // إجمالي مبلغ الطلبات للفترة (جميع الطلبات)
            $currentOrders = Order::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                ->sum('total') ?? 0;

            // حساب الفترة السابقة للمقارنة
            $daysDiff = $dateRange['end']->diffInDays($dateRange['start']) + 1;
            $previousStart = $dateRange['start']->copy()->subDays($daysDiff);
            $previousEnd = $dateRange['start']->copy()->subDay();

            $previousRevenue = Order::where('status', 'delivered')
                ->whereNotNull('delivered_date')
                ->whereBetween('delivered_date', [$previousStart, $previousEnd])
                ->sum('total') ?? 0;

            $previousOrders = Order::whereBetween('created_at', [$previousStart, $previousEnd])
                ->sum('total') ?? 0;

            // حساب نسبة التغيير مع معالجة القسمة على صفر
            $revenueChange = 0;
            if ($previousRevenue > 0) {
                $revenueChange = (($currentRevenue - $previousRevenue) / $previousRevenue) * 100;
            } elseif ($currentRevenue > 0) {
                $revenueChange = 100; // زيادة 100% إذا لم تكن هناك إيرادات سابقة
            }

            $ordersChange = 0;
            if ($previousOrders > 0) {
                $ordersChange = (($currentOrders - $previousOrders) / $previousOrders) * 100;
            } elseif ($currentOrders > 0) {
                $ordersChange = 100; // زيادة 100% إذا لم تكن هناك طلبات سابقة
            }

            return [
                'current_revenue' => floatval($currentRevenue),
                'current_orders' => floatval($currentOrders),
                'revenue_change' => round($revenueChange, 2),
                'orders_change' => round($ordersChange, 2)
            ];
        } catch (\Exception $e) {
            // تسجيل الخطأ للمراجعة
            Log::error('خطأ في حساب بيانات الإيرادات: ' . $e->getMessage());

            // في حالة حدوث خطأ، إرجاع قيم افتراضية
            return [
                'current_revenue' => 0,
                'current_orders' => 0,
                'revenue_change' => 0,
                'orders_change' => 0
            ];
        }
    }

    /**
     * الحصول على بيانات الرسم البياني
     */
    private function getChartData()
    {
        try {
            $chartData = [
                'revenue' => [],
                'orders' => [],
                'canceled' => [],
                'labels' => []
            ];

            // تحديد الفترة الزمنية للرسم البياني (آخر 12 شهر)
            for ($i = 11; $i >= 0; $i--) {
                $month = Carbon::now()->subMonths($i);
                $monthStart = $month->copy()->startOfMonth();
                $monthEnd = $month->copy()->endOfMonth();

                $chartData['labels'][] = $month->format('M');

                // إيرادات الشهر (الطلبات المسلمة)
                $monthRevenue = Order::where('status', 'delivered')
                    ->whereNotNull('delivered_date')
                    ->whereBetween('delivered_date', [$monthStart, $monthEnd])
                    ->sum('total') ?? 0;

                // طلبات الشهر (جميع الطلبات)
                $monthOrders = Order::whereBetween('created_at', [$monthStart, $monthEnd])
                    ->sum('total') ?? 0;

                // طلبات ملغاة
                $monthCanceled = Order::where('status', 'canceled')
                    ->whereBetween('created_at', [$monthStart, $monthEnd])
                    ->sum('total') ?? 0;

                $chartData['revenue'][] = floatval($monthRevenue);
                $chartData['orders'][] = floatval($monthOrders);
                $chartData['canceled'][] = floatval($monthCanceled);
            }

            return $chartData;
        } catch (\Exception $e) {
            Log::error('خطأ في جلب بيانات الرسم البياني: ' . $e->getMessage());

            // إرجاع بيانات فارغة في حالة الخطأ
            return [
                'revenue' => array_fill(0, 12, 0),
                'orders' => array_fill(0, 12, 0),
                'canceled' => array_fill(0, 12, 0),
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            ];
        }
    }

    // Color Management
    public function colors()
    {
        $colors = Color::withCount('products')->orderBy('id', 'DESC')->paginate(10);
        return view('admin.colors', compact('colors'));
    }

    public function add_color()
    {
        return view('admin.color-add');
    }

    public function color_store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:colors,code',
            'hex_code' => 'nullable|string|max:7',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_active' => 'required|boolean'
        ]);

        $color = new Color();
        $color->name = $request->name;
        $color->code = $request->code;
        $color->hex_code = $request->hex_code;
        $color->description = $request->description;
        $color->order = $request->order ?? 0;
        $color->is_active = $request->is_active;
        $color->save();

        Cache::forget('search_filters');

        return redirect()->route('admin.colors')->with('status', 'تم إضافة اللون بنجاح!');
    }

    public function color_edit($id)
    {
        $color = Color::findOrFail($id);
        return view('admin.color-edit', compact('color'));
    }

    public function color_update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:colors,code,' . $request->id,
            'hex_code' => 'nullable|string|max:7',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_active' => 'required|boolean'
        ]);

        $color = Color::findOrFail($request->id);
        $color->name = $request->name;
        $color->code = $request->code;
        $color->hex_code = $request->hex_code;
        $color->description = $request->description;
        $color->order = $request->order ?? 0;
        $color->is_active = $request->is_active;
        $color->save();

        Cache::forget('search_filters');

        return redirect()->route('admin.colors')->with('status', 'تم تحديث اللون بنجاح!');
    }

    public function color_delete($id)
    {
        $color = Color::findOrFail($id);
        $color->delete();
        
        Cache::forget('search_filters');
        
        return redirect()->route('admin.colors')->with('status', 'تم حذف اللون بنجاح!');
    }

    // Size Management
    public function sizes()
    {
        $sizes = Size::withCount('products')->orderBy('id', 'DESC')->paginate(10);
        return view('admin.sizes', compact('sizes'));
    }

    public function add_size()
    {
        return view('admin.size-add');
    }

    public function size_store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:sizes,code',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_active' => 'required|boolean'
        ]);

        $size = new Size();
        $size->name = $request->name;
        $size->code = $request->code;
        $size->description = $request->description;
        $size->order = $request->order ?? 0;
        $size->is_active = $request->is_active;
        $size->save();

        Cache::forget('search_filters');

        return redirect()->route('admin.sizes')->with('status', 'تم إضافة المقاس بنجاح!');
    }

    public function size_edit($id)
    {
        $size = Size::findOrFail($id);
        return view('admin.size-edit', compact('size'));
    }

    public function size_update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:sizes,code,' . $request->id,
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_active' => 'required|boolean'
        ]);

        $size = Size::findOrFail($request->id);
        $size->name = $request->name;
        $size->code = $request->code;
        $size->description = $request->description;
        $size->order = $request->order ?? 0;
        $size->is_active = $request->is_active;
        $size->save();

        Cache::forget('search_filters');

        return redirect()->route('admin.sizes')->with('status', 'تم تحديث المقاس بنجاح!');
    }

    public function size_delete($id)
    {
        $size = Size::findOrFail($id);
        $size->delete();

        Cache::forget('search_filters');

        return redirect()->route('admin.sizes')->with('status', 'تم حذف المقاس بنجاح!');
    }

    // Slideshow Management (الشرائح المتحركة)
    public function slides()
    {
        $slides = Slide::ordered()->paginate(10);
        return view('admin.slides', compact('slides'));
    }

    public function add_slide()
    {
        return view('admin.slide-add');
    }

    public function slide_store(Request $request)
    {
        $request->validate([
            'tagline' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'link' => 'nullable|string|max:255',
            'image' => 'required|mimes:png,jpg,jpeg,webp|max:2048',
            'status' => 'required|boolean',
            'order' => 'nullable|integer'
        ]);

        $slide = new Slide();
        $slide->tagline = $request->tagline;
        $slide->title = $request->title;
        $slide->subtitle = $request->subtitle;
        $slide->link = $request->link;
        $slide->status = $request->status;
        $slide->order = $request->order ?? 0;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $file_name = Str::uuid() . '.' . $image->getClientOriginalExtension();
            
            $this->GenerateSlideImage($image, $file_name);
            $slide->image = $file_name;
        }

        $slide->save();

        Cache::forget('home_slides');

        return redirect()->route('admin.slides')->with('status', 'تم إضافة الشريحة بنجاح!');
    }

    public function slide_edit($id)
    {
        $slide = Slide::findOrFail($id);
        return view('admin.slide-edit', compact('slide'));
    }

    public function slide_update(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:slides,id',
            'tagline' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'link' => 'nullable|string|max:255',
            'image' => 'nullable|mimes:png,jpg,jpeg,webp|max:2048',
            'status' => 'required|boolean',
            'order' => 'nullable|integer'
        ]);

        $slide = Slide::findOrFail($request->id);
        $slide->tagline = $request->tagline;
        $slide->title = $request->title;
        $slide->subtitle = $request->subtitle;
        $slide->link = $request->link;
        $slide->status = $request->status;
        $slide->order = $request->order ?? 0;

        if ($request->hasFile('image')) {
            if ($slide->image && File::exists(public_path('uploads/slides') . '/' . $slide->image)) {
                File::delete(public_path('uploads/slides') . '/' . $slide->image);
            }

            $image = $request->file('image');
            $file_name = Str::uuid() . '.' . $image->getClientOriginalExtension();
            
            $this->GenerateSlideImage($image, $file_name);
            $slide->image = $file_name;
        }

        $slide->save();

        Cache::forget('home_slides');

        return redirect()->route('admin.slides')->with('status', 'تم تحديث الشريحة بنجاح!');
    }

    public function slide_delete($id)
    {
        $slide = Slide::findOrFail($id);
        if ($slide->image && File::exists(public_path('uploads/slides') . '/' . $slide->image)) {
            File::delete(public_path('uploads/slides') . '/' . $slide->image);
        }
        $slide->delete();

        Cache::forget('home_slides');

        return redirect()->route('admin.slides')->with('status', 'تم حذف الشريحة بنجاح!');
    }

    private function GenerateSlideImage($image, $imageName)
    {
        $destinationPath = public_path('uploads/slides');
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true, true);
        }
        
        $img = Image::read($image->path());
        $img->resize(600, 800, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upSize();
        })->save($destinationPath . '/' . $imageName);
    }
}
