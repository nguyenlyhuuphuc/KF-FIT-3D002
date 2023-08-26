<?php

use App\Http\Controllers\Admin\ProductCategory;
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
    Route::get('product_category', [ProductCategory::class, 'index'])->name('product_category.list');
    Route::get('product_category/add', [ProductCategory::class, 'add'])->name('product_category.add');
    Route::post('product_category/store', [ProductCategory::class, 'store'])->name('product_category.store');
});