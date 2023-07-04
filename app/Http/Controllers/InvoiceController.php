<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\ItemStock;
use App\Models\Item;
use Illuminate\Http\Request;

class InvoiceController extends Controller
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

        $inventoryQuery = InvoiceItem::with(['invoice','invoice.user','invoice.customer','item'])->orderBy('id', 'DESC')->select('id', 'invoice_id', 'item_id', 'item_price', 'item_quantity', 'total_price')
            ->where(function ($query) use ($request) {
                $query->where('invoice_id', 'like', '%' . $request->input('searchtext') . '%');  
            })->paginate($per_page);

        return response()->json([
            'success' => true,
            'message' => 'Success',
            'data' => $inventoryQuery,
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
            'customer_id' => 'required|integer',
            'amount' => 'required|integer',
            'status' => 'required|string',
            'issue_date' => 'required|date',
            'due_date' => 'required|date',
            'payment_method' => 'required|string',
            'items'=> 'required|array'
        ]);

        $user = auth()->user();

        $paymentRef = 'INV'.'-'.substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 9);

        $data = Invoice::create([
            'customer_id'=> $input['customer_id'],
            'amount'=>  $input['amount'],
            'status'=>  $input['status'],
            'issue_date'=>  $input['issue_date'],
            'due_date'=> $input['due_date'],
            'payment_method'=> $input['payment_method'],
            'payment_ref'=> $paymentRef,
            'user_id'=> $user->id
           ]);

           foreach ($input['items'] as $row) {
            $totalcost = $row['item_price'] * $row['item_quantity'];

            InvoiceItem::create([
                'invoice_id'=> $data->id,
                'item_id'=> $row['item_id'],
                'item_price'=> $row['item_price'],
                'item_quantity'=> $row['item_quantity'],
                'total_price'=> $totalcost
             ]);

             $stock = Item::find($row['item_id']);
             if($stock['stock'] > $row['item_quantity']){

                // reduce item stocks
              $data = ItemStock::create([
                'item_id'=>  $row['item_id'],
                'stock_out'=>  $row['item_quantity'],
                'user_id'=> $user->id
               ]);

               // update item 
               $stock = Item::find($row['item_id']);
               $existing_stock = $stock['stock'];
               $updated_stock = $existing_stock - $row['item_quantity'];
               $stock->update([
                'stock'=>  $updated_stock
               ]);

             }

             





           }

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
        $data = InvoiceItem::with(['invoice','invoice.user','invoice.customer','item'])->where('invoice_id',$id)->get();
        return response()->json(['success' => true, 'message' => 'Invoice!', 'data' => $data]);
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
