<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemStock;
use Illuminate\Http\Request;

class ItemStockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->validate([
            'item_id' => 'required|integer',
            'stock_in' => 'required|integer'
        ]);

        $user = auth()->user();
        $data = ItemStock::create([
            'item_id'=>  $input['item_id'],
            'stock_in'=>  $input['stock_in'],
            'user_id'=> $user->id
           ]);


           $stock = Item::find($input['item_id']);
           $existing_stock = $stock['stock'];
           $updated_stock = $existing_stock + $input['stock_in'];
           $stock->update([
            'stock'=>  $updated_stock
           ]);



           return response()->json([
            'success' => true,
            'message' => 'Item Stock Created Successfully!',
            'data' => $data,
        ], 200);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
