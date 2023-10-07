<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(){
        $arrayDatas = [];
        $arrayDatas[] =  ['Order Status', 'Number'];
        $dataOrders = DB::table('orders')
        ->selectRaw('status, count(status) as number')
        ->groupBy('status')
        ->get();

        foreach($dataOrders as $data){
            $arrayDatas[] = [$data->status, $data->number];
        }

        return view('admin.pages.dashboard', ['arrayDatas' => $arrayDatas]);
    }
}
