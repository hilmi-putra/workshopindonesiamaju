<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        return view('store.cart');
    }
}
