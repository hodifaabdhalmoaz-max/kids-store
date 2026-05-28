<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Store a new review for a product.
     */
    public function store(Request $request, $product_id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:5|max:1000',
        ], [
            'rating.required' => 'يرجى اختيار تقييم',
            'rating.min' => 'يرجى اختيار تقييم من 1 إلى 5',
            'rating.max' => 'يرجى اختيار تقييم من 1 إلى 5',
            'comment.required' => 'يرجى كتابة تقييمك',
            'comment.min' => 'يجب أن يكون التقييم 5 أحرف على الأقل',
            'comment.max' => 'يجب ألا يتجاوز التقييم 1000 حرف',
        ]);

        $product = Product::findOrFail($product_id);

        // Check if user already reviewed this product
        $existingReview = Review::where('product_id', $product_id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingReview) {
            return redirect()->back()->with('error', 'لقد قمت بتقييم هذا المنتج مسبقاً.');
        }

        Review::create([
            'product_id' => $product_id,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => true, // Auto-approve for now
        ]);

        return redirect()->back()->with('success', 'تم إضافة تقييمك بنجاح! شكراً لك.');
    }
}
