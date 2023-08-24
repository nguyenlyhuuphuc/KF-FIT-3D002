<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(){
        //Danh sach user
        $users = DB::select('select * from users');
        
        //Pass variable to view
        return view('admin.pages.user.list', ['users' => $users]);
    }
}
