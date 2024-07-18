<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request; 
use Illuminate\Support\Str; 
use App\Http\Controllers\AuthController;
use App\Http\Controllers\admin\NawCategoryController;
use App\Http\Controllers\admin\TempImagesController;
use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\admin\BrandController;
use App\Http\Controllers\admin\ProductControlller;
use App\Http\Controllers\admin\ProductImageController;
use App\Http\Controllers\admin\ProductSubCategoryController;
use App\Http\Controllers\admin\DashboardController;

use App\Http\Controllers\FrontController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

    Route::get('/', function () {
        return view('welcome');
    })->name('home');

// Registration Form
Route::get('/register', function () {
    return view('auth.register');
})->name('register.form');

// Login Form
Route::get('/login', function () {
    return view('auth.login');
})->name('login.form');

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/supplier/change-password',[AuthController::class,'showChangePasswordForm'])->name('supplier.changePassword');
Route::post('/supplier/process-change-password',[AuthController::class,'changePassword'])->name('supplier.processChangePassword');

Route::get('/', [FrontController::class,'index'])->name('front.home');
Route::get('/shop/{categorySlug?}/{subCategorySlug?}', [Shopcontroller::class,'index'])->name('front.shop');
Route::get('/product/{slug}', [Shopcontroller::class,'product'])->name('front.product');
Route::get('/cart', [CartController::class,'cart'])->name('front.cart');
Route::post('/add-to-cart', [CartController::class,'addToCart'])->name('front.addToCart');
Route::post('/update-cart', [CartController::class,'updateCart'])->name('front.updateCart');
Route::post('/delete-item', [CartController::class,'deleteItem'])->name('front.deleteItem.cart');
Route::get('/checkout', [CartController::class,'checkout'])->name('front.checkout');
Route::post('/process-checkout', [CartController::class,'processCheckout'])->name('front.processCheckout');
Route::get('/thanks/{orderId}', [CartController::class,'thankyou'])->name('front.thankyou');
// Role-based routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        Log::info('Admin Dashboard route hit');
        return view('admin.dashboard');
    })->name('admin.dashboard');
});


Route::middleware(['auth', 'role:supplier'])->group(function () {
    Route::get('/supplier/dashboard', function () {
        return view('supplier.dashboard');
    })->name('supplier.dashboard');
    
    Route::get('/supplier/dashboard', [DashboardController::class, 'index'])->name('supplier.dashboard');

    Route::get('/categories', [NawCategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [NawCategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [NawCategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [NawCategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [NawCategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [NawCategoryController::class, 'destroy'])->name('categories.delete');

    //sub_category
    Route::get ('/sub-categories', [SubCategoryController::class,'index'])->name('sub-categories.index');
    Route::get ('/sub-categories/create', [SubCategoryController::class,'create'])->name('sub-categories.create');
    Route::post('/sub-categories', [SubCategoryController::class,'store'])->name('sub-categories.store');
    Route::get ('/sub-categories/{subCategory}/edit', [SubCategoryController::class,'edit'])->name('sub-categories.edit');
    Route::put ('/sub-categories/{subCategory}', [SubCategoryController::class,'update'])->name('sub-categories.update');
    Route::delete ('/sub-categories/{subCategory}', [SubCategoryController::class,'destroy'])->name('sub-categories.delete');

    //Brands 
    Route::get ('/brands', [BrandController::class,'index'])->name('brands.index');
    Route::get ('/brands/create', [BrandController::class,'create'])->name('brands.create');
    Route::post('/brands', [BrandController::class,'store'])->name('brands.store');
    Route::get ('/brands/{brand}/edit', [BrandController::class,'edit'])->name('brands.edit');
    Route::put ('/brands/{brand}', [BrandController::class,'update'])->name('brands.update');
    Route::delete ('/brands/{brand}', [BrandController::class,'destroy'])->name('brands.delete');

    //Procuts 
    Route::get ('/products', [ProductControlller::class,'index'])->name('products.index');
    Route::get ('/products/create', [ProductControlller::class,'create'])->name('products.create');
    Route::post('/products', [ProductControlller::class,'store'])->name('products.store');
    Route::get ('/products/{product}/edit', [ProductControlller::class,'edit'])->name('products.edit');
    Route::put ('/products/{product}', [ProductControlller::class,'update'])->name('products.update');
    Route::delete ('/products/{product}', [ProductControlller::class,'destroy'])->name('products.delete');
    Route::get('/get-product', [ProductControlller::class,'getProducts'])->name('products.getProducts');
    Route::get ('/ratings', [ProductControlller::class,'productRatings'])->name('products.productRatings');
    Route::get ('/status-rating-change', [ProductControlller::class,'changeRatingsStatus'])->name('productschangeRatingsStatus');
    Route::delete ('/ratings/{rating}', [ProductControlller::class,'destroyrating'])->name('ratings.delete');

    //product sub-category
    Route::get ('/product-subcategories', [ProductSubCategoryController::class,'index'])->name('product-subcategories.index');
        
    Route::post ('/product-images/update', [ProductImageController::class,'update'])->name('product-images.update');
    Route::delete ('/product-images', [ProductImageController::class,'destroy'])->name('product-images.destroy');
});

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user/dashboard', function () {
        return view('user.dashboard');
    })->name('user.dashboard');
});

    // Temporary image upload route
    Route::post('/upload-temp-image', [TempImagesController::class, 'create'])->name('temp-images.create');

    // Slug generation route
    Route::get('/getSlug', function (Request $request) {
        $slug = '';
        if (!empty($request->title)) {
            $slug = Str::slug($request->title);
        }
        return response()->json([
            'status' => true,
            'slug' => $slug
        ]);
    })->name('getSlug');