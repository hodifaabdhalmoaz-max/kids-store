<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Surfsidemedia\Shoppingcart\Facades\Cart;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $items = Cart::instance('wishlist')->content();
        return view('wishlist', compact('items'));
    }
    public function add_to_wishlist(Request $request)
    {
       Cart::instance('wishlist')->add($request->id,$request->name,$request->quantity,$request->price)->associate('App\Models\Product');
       
       if(Auth::check()) {
           Wishlist::updateOrCreate(
               ['user_id' => Auth::id(), 'product_id' => $request->id]
           );
       }
       
       return redirect()->back();
    }
    public function remove_item($rowId)
    {
       $item = Cart::instance('wishlist')->get($rowId);
       if(Auth::check() && $item) {
           Wishlist::where('user_id', Auth::id())->where('product_id', $item->id)->delete();
       }
       
       Cart::instance('wishlist')->remove($rowId);
       return redirect()->back();
    }
    
    public function remove_item_by_id($id)
    {
        if(Auth::check()) {
            Wishlist::where('user_id', Auth::id())->where('product_id', $id)->delete();
        }
        
        $item = Cart::instance('wishlist')->content()->where('id', $id)->first();
        if($item) {
            Cart::instance('wishlist')->remove($item->rowId);
        }
        
        return redirect()->back();
    }
    public function empty_wishlist()
    {
       Cart::instance('wishlist')->destroy();
       return redirect()->back();
    }
    public function move_to_cart($rowId)
    {
       $item = Cart::instance('wishlist')->get($rowId);
       Cart::instance('wishlist')->remove($rowId);
       Cart::instance('cart')->add($item->id,$item->name,$item->qty,$item->price)->associate('App\Models\Product');
       return redirect()->back();
    }
}
