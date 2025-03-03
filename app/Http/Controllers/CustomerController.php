<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Category;
use App\Models\Contact;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use Inertia\Inertia;
use DB;


class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $customers = Customer::with('category')->withCount('contacts')->get();
        $categories = Category::all();
        return Inertia::render('Customers/Index', [
            'customers' => $customers,
            'categories' => $categories
        ]);
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
        //
        $request->validate([
            'name' => 'required|string|max:255',
            'reference' => 'required|string|unique:customers',
            'category_id' => 'required|exists:categories,id',
            'start_date' => 'required|date',
            'description' => 'nullable|string',
            'contacts' => 'array',
            'contacts.*.first_name' => 'required|string|max:255',
            'contacts.*.last_name' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($request) {
            $customer = Customer::create($request->only(['name', 'reference', 'category_id', 'start_date', 'description']));

            foreach ($request->contacts as $contact) {
                $customer->contacts()->create($contact);
            }
        });
        
        return redirect()->route('customers.index')->with('success', 'Customer data saved successfully.');
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
            'description' => 'nullable|string',
            'contacts' => 'array',
            'contacts.*.id' => 'nullable|exists:contacts,id', // Validate existing contact IDs
            'contacts.*.first_name' => 'required|string|max:255',
            'contacts.*.last_name' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($request, $customer) {
            // Update Customer Details
            $customer->update($request->only(['name', 'reference', 'category_id', 'start_date', 'description']));

            $existingContactIds = $customer->contacts()->pluck('id')->toArray();
            $incomingContactIds = collect($request->contacts)->pluck('id')->filter()->toArray();

            // Delete removed contacts
            $contactsToDelete = array_diff($existingContactIds, $incomingContactIds);
            Contact::whereIn('id', $contactsToDelete)->delete();

            // Update or Create Contacts
            foreach ($request->contacts as $contactData) {
                if (isset($contactData['id'])) {
                    // Update existing contact
                    Contact::where('id', $contactData['id'])->update([
                        'first_name' => $contactData['first_name'],
                        'last_name' => $contactData['last_name'],
                    ]);
                } else {
                    // Create new contact
                    $customer->contacts()->create([
                        'first_name' => $contactData['first_name'],
                        'last_name' => $contactData['last_name'],
                    ]);
                }
            }
        });

        return redirect()->route('customers.index')->with('success', 'Customer data updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        //
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer data deleted successfully.');
    }
}
