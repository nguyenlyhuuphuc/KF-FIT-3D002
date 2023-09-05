<?php

use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';



Route::prefix('admin')->name('admin.')->group(function(){
    //Product
    Route::get('product', [ProductController::class, 'index'])->name('product.list');

    //User
    Route::get('user', [UserController::class, 'index'])->name('user.list');
    
    //Product Category
    Route::get('product_category', [ProductCategoryController::class, 'index'])->name('product_category.list');
    Route::get('product_category/add', [ProductCategoryController::class, 'add'])->name('product_category.add');
    Route::post('product_category/store', [ProductCategoryController::class, 'store'])->name('product_category.store');
    Route::get('product_category/{id}', [ProductCategoryController::class, 'detail'])->name('product_category.detail');
    Route::post('product_category/update/{id}', [ProductCategoryController::class,'update'])->name('product_category.update');
    Route::get('product_category/destroy/{id}', [ProductCategoryController::class, 'destroy'])->name('product_category.destroy');
});
