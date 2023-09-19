<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //SELECT products.*, product_categories.name as product_category_name FROM `products` 
        //LEFT JOIN product_categories ON products.product_category_id = product_categories.id
        //ORDER BY created_at desc
        //LIMIT 0, 3

        //Query Builder
        $products = DB::table('products')
        ->select('products.*', 'product_categories.name as product_category_name')
        ->leftJoin('product_categories', 'products.product_category_id', '=', 'product_categories.id')
        ->orderBy('created_at', 'desc')
        ->get();
        // ->paginate(config('my-config.item-per-pages'));

        return view('admin.pages.product.list', ['products' => $products]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $productCategories = DB::select('select * from product_categories where status = 1');
        return view('admin.pages.product.create', ['productCategories' => $productCategories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        if($request->hasFile('image')){
            $fileOrginialName = $request->file('image')->getClientOriginalName();
            $fileName = pathinfo($fileOrginialName, PATHINFO_FILENAME);
            $fileName .= '_'.time().'.'.$request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('images'),  $fileName);
        }
        
        //Query Builder
        $check = DB::table('products')->insert([
            "name" => $request->name,
            "slug" => $request->slug,
            "price" => $request->price,
            "discount_price" =>$request->discount_price,
            "short_description" => $request->short_description,
            "description" => $request->description,
            "information" => $request->information,
            "qty" => $request->qty,
            "shipping" => $request->shipping,
            "weight" =>$request->weight,
            "status" => $request->status,
            "product_category_id" => $request->product_category_id,
            "image" => $fileName ?? null,
            "created_at" => Carbon::now(),
            "updated_at" => Carbon::now()
        ]);

        $message = $check ? 'tao san pham thanh cong' : 'tao san pham that bai';
        //session flash
        return redirect()->route('admin.product.index')->with('message', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = DB::table('products')->find($id);
        $productCategories = DB::table('product_categories')->where('status','=', 1)->get();
        return view('admin.pages.product.detail', ['product' => $product,'productCategories' => $productCategories]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, string $id)
    {
        $product = DB::table('products')->find($id);
        $oldImageFileName = $product->image;

        if($request->hasFile('image')){
            $fileOrginialName = $request->file('image')->getClientOriginalName();
            $fileName = pathinfo($fileOrginialName, PATHINFO_FILENAME);
            $fileName .= '_'.time().'.'.$request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('images'),  $fileName);

            if(!is_null($oldImageFileName) && file_exists('images/'.$oldImageFileName)){
                unlink('images/'.$oldImageFileName);
            }
        }

        $check = DB::table('products')->where('id', '=', $id)->update([
            "name" => $request->name,
            "slug" => $request->slug,
            "price" => $request->price,
            "discount_price" =>$request->discount_price,
            "short_description" => $request->short_description,
            "description" => $request->description,
            "information" => $request->information,
            "qty" => $request->qty,
            "shipping" => $request->shipping,
            "weight" =>$request->weight,
            "status" => $request->status,
            "product_category_id" => $request->product_category_id,
            "image" => $fileName ?? $oldImageFileName,
            "updated_at" => Carbon::now()
        ]);

        $message = $check ? 'cap nhat san pham thanh cong' : 'cap nhat san pham that bai';
        //session flash
        return redirect()->route('admin.product.index')->with('message', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // $result = DB::table('products')->where('id',$id)->delete();
        $product = DB::table('products')->find($id);
        $image = $product->image;
        if(!is_null($image) && file_exists('images/'.$image)){
            unlink('images/'.$image);
        }
        
        //QueryBuidlder
        // $result = DB::table('products')->delete($id);
        //ELoquent
        $productData = Product::find((int)$id);
        $productData->delete();

        //session flash
        return redirect()->route('admin.product.index')->with('message','xoa san pham thanh cong');
    }
    
    public function createSlug(Request $request){
        return response()->json(['slug' => Str::slug($request->name, '-')]);
    }

    public function uploadImage(Request $request){
        if($request->hasFile('upload')){
            $fileOrginialName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($fileOrginialName, PATHINFO_FILENAME);
            $fileName .= '_'.time().'.'.$request->file('upload')->getClientOriginalExtension();
            $request->file('upload')->move(public_path('images'),  $fileName);

            $url = asset('images/' . $fileName);
            return response()->json(['fileName' => $fileName, 'uploaded'=> 1, 'url' => $url]);
        }
    }
}
