<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductCategoryRequest;
use App\Http\Requests\UpdateProductCategoryRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductCategoryController extends Controller
{
    public function index(Request $request){
        $keyword = $request->keyword ?? '';
        $sortBy = $request->sortBy ?? 'latest';
        $sort = ($sortBy === 'oldest') ? 'asc' : 'desc';
    
        $page = $request->page ?? 1;
        $itemPerPage = 2;
        $offset = ($page - 1) * $itemPerPage;

        $sqlSelect = 'select * from product_categories ';
        $paramsBinding = [];
        if(!empty($keyword)){
            $sqlSelect .= 'where name like ?';
            $paramsBinding[] = '%'.$keyword.'%';
        }
        $sqlSelect .= ' order by created_at '.$sort;
        $sqlSelect .= ' limit ?,?';
        $paramsBinding[] = $offset;
        $paramsBinding[] = $itemPerPage;

        $productCategories = DB::select($sqlSelect, $paramsBinding);
        $totalRecords = DB::select('select count(*) as sum from product_categories')[0]->sum;
        
        $totalPages = ceil($totalRecords / $itemPerPage);

        return view('admin.pages.product_category.list', 
            [
                'productCategories' => $productCategories,
                'totalPages' => $totalPages,
                'currentPage' => $page,
                'keyword' => $keyword,
                'sortBy' => $sortBy
            ]
        );
    }

    public function add(){
        return view('admin.pages.product_category.create');
    }

    public function store(StoreProductCategoryRequest $request){
        $bool = DB::insert('INSERT INTO product_categories(name, status, created_at, updated_at) VALUES (?,?,?,?)', [
            $request->name,
            $request->status,
            Carbon::now(),
            Carbon::now()
        ]);
        $message = $bool ? 'tao thanh cong' : 'tao that bai';

        //session flash
        return redirect()
        ->route('admin.product_category.list')
        ->with('message', $message);
    }

    public function detail($id){
        $productCategory = DB::select('select * from product_categories where id = ?', [$id]);        
        return view('admin.pages.product_category.detail', ['productCategory' => $productCategory[0]]);
    }

    public function update(UpdateProductCategoryRequest $request, $id){

        //UPDATE `product_categories` SET name='', status='' WHERE id=1;
        $check = DB::update('UPDATE `product_categories` SET name = ?, status = ? WHERE id = ?', 
        [$request->name, $request->status, $id]);

        $message = $check > 0 ? 'cap nhat thanh cong' : 'cap nhat that bai';
        //session flash
        return redirect()
        ->route('admin.product_category.list')
        ->with('message', $message);
    }

    public function destroy($id){
        $check = DB::delete('delete from product_categories where id = ? ', [$id]);
        $message = $check > 0 ? 'xoa thanh cong' : 'xoa that bai';
        //session flash
        return redirect()->route('admin.product_category.list')->with('message', $message);
    }
}
