<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use function PHPUnit\Framework\returnSelf;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = User::latest()->get();
        return response()->json([
            'msg'=>'List Users',
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
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6',
            'image'=>'mimes:png,jpg|required|max:2048'
        ]);

        $image = $request->file('image');
        $image->storeAs('public/users',$image->hashName());

        $validate['image']=$image->hashName();

        $data = User::create($validate);

        return response()->json([
            'msg'=>'success created user',
            'data'=>$data
        ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data = User::find($id);

        if(!$data){
            return response()->json([
                'msg'=>'User Not Found'
            ],404);
        };

        return response()->json([
            'msg'=>'User Detail',
            'data'=>$data
        ],200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = User::find($id);

        if(!$data){
            return response()->json([
                'msg'=>'User Not Found'
            ],404);
        };

        $validate = $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users,email,id,'.$id,
            'password'=>'required|min:6',
            'image'=>'mimes:png,jpg|nullable|max:2048'
        ]);

        if($request->hasFile('image')){
            Storage::delete('public/users/'.basename($data->image));

            $image = $request->file('image');
            $image->storeAs('public/users',$image->hashName());

            $validate['image']=$image->hashName();
        };

        $datas = $data->update($validate);

        return response()->json([
            'msg'=>'success updated data',
            'data'=>$datas
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = User::find($id);

        if(!$data){
            return response()->json([
                'msg'=>'User Not Found'
            ],404);
        };

        Storage::delete('public/users/'.basename($data->image));

        $data->delete();

        return response()->json([
            'msg'=>'success deleted user'
        ],200);

    }
}
