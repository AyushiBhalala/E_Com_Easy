<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Brand;
use App\Models\Product;



class DashboardController extends Controller
{
    public function index()
    {
        $totalCategories = Category::count();
        $totalSubCategories = SubCategory::count();
        $totalBrands = Brand::count();
        $totalProducts = Product::count();
        $user = auth()->user();
        return view('supplier.dashboard', compact('totalCategories', 'totalSubCategories','totalBrands','totalProducts','user'));
    }
    public function showDashboard()
    {
        $user = auth()->user();
        return view('supplier.dashboard', [
            'user' => $user
        ]);
    }

}
