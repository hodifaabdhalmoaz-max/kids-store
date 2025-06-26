<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        return view('user.index');
    }

    public function dashboard(){
        return view('user.dashboard');
    }

    public function profile(){
        return view('user.profile');
    }

    public function orders(){
        return view('user.orders');
    }

    public function wishlist(){
        return view('user.wishlist');
    }
}
