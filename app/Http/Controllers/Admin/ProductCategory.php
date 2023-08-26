<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductCategory extends Controller
{
    public function index(){
        return view('admin.pages.product_category.list');
    }

    public function add(){
        return view('admin.pages.product_category.create');
    }

    public function store(){
        dd(1);
    }
}
