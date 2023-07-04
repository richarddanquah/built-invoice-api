<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page = 50;
        if ($request->input('per_page')) {
            $per_page = $request->input('per_page');
        } else {
            $per_page = 50;
        }

        $itemQuery = Item::with(['user'])->orderBy('id', 'DESC')->select('id', 'name', 'image_url', 'price', 'description', 'stock','user_id')
            ->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->input('searchtext') . '%');  
            })->paginate($per_page);

        return response()->json([
            'success' => true,
            'message' => 'Success',
            'data' => $itemQuery,
        ], 200);
        
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
            'name' => 'required|string|unique:items,name',
            'price' => 'required|integer',
            'description' => 'required|string',
            'image_url' => 'required|string',
        ]);
        $user = auth()->user();

        $data = Item::create([
            'name'=>  $input['name'],
            'price'=>  $input['price'],
            'description'=>  $input['description'],
            'image_url'=>  $input['image_url'],
            'user_id'=> $user->id
           ]);
   
           return response()->json([
               'success' => true,
               'message' => 'Item Created Successfully!',
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
        $data = Item::find($id);

        return response()->json(['success' => true, 'message' => 'Item!', 'data' => $data]);
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
        $input = $request->validate([
            'name' => 'required|string',
            'price' => 'required|integer',
            'description' => 'required|string',
            'image_url' => 'required|string',
        ]);

        $user = auth()->user();

        $item = Item::find($id);

        $item->update([
            'name'=>  $input['name'],
            'price'=>  $input['price'],
            'description'=>  $input['description'],
            'image_url'=>  $input['image_url'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Item Updated Successfully',
            'data' => $item
        ], 200);
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
