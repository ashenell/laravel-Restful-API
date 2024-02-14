<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Customer;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CustomerResource;
use App\Http\Resources\V1\CustomerCollection;
use App\Filters\V1\CustomersFilter;
use Illuminate\Http\Request;
use App\Http\Requests\V1\StoreCustomerRequest;
use App\Http\Requests\V1\UpdateCustomerRequest;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new CustomersFilter();
        $filterItems = $filter->transform($request); // Transform the request into a query [['column', 'operator', 'value']]
        
        $includeInvoices = $request->query('includeInvoices'); // Get the includeInvoices query parameter
        $customers = Customer::where($filterItems); // Get the customers with the filter items
        
        if ($includeInvoices) {
            $customers = $customers->with('invoices');
        }
        return new CustomerCollection($customers->paginate()->appends($request->query()));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        return new CustomerResource(Customer::create($request->all())); // Return the customer
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {   
        $includeInvoices = request()->query('includeInvoices'); // Get the includeInvoices query parameter
        if ($includeInvoices) {
            return new CustomerResource($customer->loadMissing('invoices')); // Load the invoices when the includeInvoices query parameter is set
        }
        return new CustomerResource($customer); // Return the customer
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $customer->update($request->all()); // Update the customer entity
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
