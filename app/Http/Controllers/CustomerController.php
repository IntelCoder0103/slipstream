<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(Customer::with('category')->withCount('contacts')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => 'required|string|max:255',
            'reference' => 'required|string|unique:customers',
            'category_id' => 'required|exists:categories,id',
            'start_date' => 'required|date',
            'description' => 'nullable|string'
        ]);

        $customer = Customer::create($request->all());
        return response()->json($customer, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        //
        return response()->json($customer->load('category', 'contacts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        //
        $request->validate([
            'name' => 'required|string|max:255',
            'reference' => 'required|string|unique:customers,reference,' . $customer->id,
            'category_id' => 'required|exists:categories,id',
            'start_date' => 'required|date',
            'description' => 'nullable|string'
        ]);

        $customer->update($request->all());
        return response()->json($customer);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        //
        $customer->delete();
        return response()->json(['message' => 'Customer deleted successfully']);
    }
}
