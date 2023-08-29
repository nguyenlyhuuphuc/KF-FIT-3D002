<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductCategoryController extends Controller
{
    public function index(){
        //Get all records in table product_categories
        $productCategories = DB::select('select * from product_categories order by created_at desc');
        return view('admin.pages.product_category.list', ['productCategories' => $productCategories]);
        // return view('admin.pages.product_category.list', compact('productCategories'));
        // return view('admin.pages.product_category.list')->with('productCategories', $productCategories);
    }

    public function add(){
        return view('admin.pages.product_category.create');
    }

    public function store(Request $request){
        //Validate data
        $request->validate([
            // 'name' => ['required', 'min:3', 'max:255'],
            'name' => 'required|min:3|max:255|unique:product_categories,name',
            'status' => 'required'
        ],
        [
            'name.required' => 'Ten buoc phai nhap!', 
            'name.min' => 'Ten phai tren 3 ky tu',
            'name.max' => 'Ten phai duoi 255 ky tu',
            'status.required' => 'Trang thai buoc phai chon!'
        ]);

        $bool = DB::insert('INSERT INTO product_categories(name, status, created_at, updated_at) VALUES (?,?,?,?)', [
            $request->name,
            $request->status,
            Carbon::now(),
            Carbon::now()
        ]);
        $message = $bool ? 'thanh cong' : 'that bai';

        //session flash
        return redirect()
        ->route('admin.product_category.list')
        ->with('message', $message);
    }

    public function detail(){
        dd(1);
    }
}
