<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;


class CustomerController extends Controller
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

        $customerQuery = Customer::orderBy('id', 'DESC')->select('id', 'fullname', 'phonenumber', 'email', 'address', 'city', 'postal_code')
            ->where(function ($query) use ($request) {
                $query->where('fullname', 'like', '%' . $request->input('searchtext') . '%')
                    ->orWhere('phonenumber', 'like', '%' . $request->input('searchtext') . '%')
                    ->orWhere('email', 'like', '%' . $request->input('searchtext') . '%');
            })->paginate($per_page);

        return response()->json([
            'success' => true,
            'message' => 'Success',
            'data' => $customerQuery,
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
            'fullname' => 'required|string',
            'phonenumber' => 'required|string|unique:customers,phonenumber',
            'address' => 'required|string',
            'email' => 'required|string|unique:customers,email',
            'city' => 'required|string',
            'postal_code' => 'required|string'
        ]);
 
    
        $data = Customer::create([
         'fullname'=>  $input['fullname'],
         'phonenumber'=>  $input['phonenumber'],
         'address'=>  $input['address'],
         'email'=>  $input['email'],
         'city'=>  $input['city'],
         'postal_code'=>  $input['postal_code'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Customer Created Successfully!',
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
        $data = Customer::find($id);
        return response()->json(['success' => true, 'message' => 'Customer!', 'data' => $data]);
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
            'fullname' => 'required',
            'phonenumber' => 'required',
            'address' => 'required',
            'email' => 'required',
            'city' => 'required',
            'postal_code' => 'required'
        ]);

        $customer = Customer::find($id);

        $customer->update([
         'fullname'=> $input['fullname'],
         'phonenumber'=> $input['phonenumber'],
         'address'=> $input['address'],
         'email'=> $input['email'],
         'city'=> $input['city'],
         'postal_code'=> $input['postal_code'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Customer Updated Successfully',
            'data' => $customer
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
        Customer::destroy($id);
        return response()->json(['success' => true, 'message' => 'Customer Deleted!']);
    }
}
