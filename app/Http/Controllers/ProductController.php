<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Product::latest()->get();

        return response()->json([
            'msg'=>'success',
            'data'=>$data
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name'=>'required|unique:products',
            'description'=>'required',
            'stok'=>'required|numeric',
            'price'=>'required|numeric',
            'image'=>'mimes:png,jpg|required|max:2048'
        ]);

        if($request->hasFile('image'))
        {
            $image = $request->file('image');
            $image->storeAs('public/products',$image->hashName());
        }
        $validate['image'] = $image->hashName();
        $data = Product::create($validate);

        return response()->json([
            'msg'=>'success created data products',
            'data'=>$data
        ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data = Product::find($id);

        if(!$data){
            return response()->json([
                'msg'=>'Data Not Found',
            ],404);
        }

        return response()->json([
            'msg'=>'Detail Product',
            'data'=>$data
        ],200);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = Product::find($id);

        if(!$data)
        {
            return response()->json([
                'msg'=>'Data Not Found',
            ],404);
        }

        $validate = $request->validate([
            'name'=>'required|unique:products,name,id,'.$id,
            'description'=>'required',
            'stok'=>'required|numeric',
            'price'=>'required|numeric',
            'image'=>'mimes:png,jpg|nullable|max:2048'
        ]);

        if($request->hasFile('image')){
            Storage::delete('public/products/'.basename($data->image));


            $image = $request->file('image');
            $image->storeAs('public/products',$image->hashName());

            $validate['image']= $image->hashName();
        }

        $datas=$data->update($validate);

        return response()->json([
            'msg'=>'success update data product',
            'data'=>$datas
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data=Product::find($id);

        Storage::delete('public/products/'.basename($data->image));
        $data->delete();
        return response()->json([
            'msg'=>"Success Deleted Product"
        ],200);
    }
}
