<?php

use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\OrderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::prefix('admin')->middleware('auth.admin')->name('admin.')->group(function(){

    //User
    Route::get('user', [UserController::class, 'index'])->name('user.list');
    
    //Product Category
    Route::get('product_category', [ProductCategoryController::class, 'index'])->name('product_category.list');
    Route::get('product_category/add', [ProductCategoryController::class, 'add'])->name('product_category.add');
    Route::post('product_category/store', [ProductCategoryController::class, 'store'])->name('product_category.store');
    Route::get('product_category/{product_category}', [ProductCategoryController::class, 'detail'])->name('product_category.detail');
    Route::post('product_category/update/{product_category}', [ProductCategoryController::class,'update'])->name('product_category.update');
    Route::get('product_category/destroy/{product_category}', [ProductCategoryController::class, 'destroy'])->name('product_category.destroy');

    //Product
    Route::resource('product', ProductController::class);
    Route::get('product/{product}/restore', [ProductController::class, 'restore'])->name('product.restore');
    Route::post('product/slug', [ProductController::class, 'createSlug'])->name('product.create.slug');
    Route::post('product/ckeditor-upload-image', [ProductController::class, 'uploadImage'])->name('product.ckedit.upload.image');
});
//Client
Route::get('/', [HomeController::class, 'index'])->name('home.index');

Route::middleware('auth')->group(function(){
    Route::get('product/add-to-cart/{productId}', [CartController::class, 'addToCart'])->name('product.add-to-cart');
    Route::get('product/delete-item-in-cart/{productId}', [CartController::class, 'deleteItem'])->name('product.delete-item-in-cart');
    Route::get('product/update-item-in-cart/{productId}/{qty?}', [CartController::class, 'updateItem'])->name('product.update-item-in-cart');
    Route::get('product/delete-item-in-cart', [CartController::class, 'emptyCart'])->name('product.delete-item-in-cart');
    Route::get('cart', [CartController::class,'index'])->name('cart.index');
    Route::get('checkout', [CartController::class, 'checkout'])->name('checkout');
    Route::post('placeorder',[OrderController::class, 'placeOrder'])->name('place-order');
});
