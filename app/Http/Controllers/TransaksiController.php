<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Transaksi::latest()->get();
        return response()->json([
            'msg'=>'list transaksi',
            'data'=>$data
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $product = $request->validate([
            'product'=>'required|array',
            'product.*.id_product'=>'required',
            'product.*.name'=>'required|string',
            'product.*.qty'=>'required|numeric|min:1',
            'product.*.total'=>'required',
        ]);
        try {
            DB::beginTransaction();


            $total_price =0 ;

            foreach ($product['product'] as $item) {
                $total_price += $item['total'];
            }


            $data = Transaksi::create([
                'id_user'=>Auth::user()->id,
                'product'=>json_encode($product),
                'price_total'=>$total_price
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
        }


    }

    /**
     * Display the specified resource.
     */
    public function show(Transaksi $transaksi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaksi $transaksi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaksi $transaksi)
    {
        //
    }
}
