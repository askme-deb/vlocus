<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\ContactUS;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ContactUSController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Contact Us Show', only: ['index','show']),
            // new Middleware('permission:Contact Us Create', only: ['create','store']),
            // new Middleware('permission:Contact Us Edit', only: ['edit','update']),
            new Middleware('permission:Contact Us Delete', only: ['destroy']),
        ];
    }

    public function index()
    {
        $items = ContactUS::latest()->get();  
        return view('admin.contact_us.index',compact('items'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|digits:10',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);
    
        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        // Save data to the database
        ContactUS::create($request->only(['name', 'email', 'phone', 'subject', 'message']));
    
        // Redirect back with success message
        return redirect()->back()->with('success', 'Your message has been sent successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ContactUS $contactUS)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ContactUS $contactUS)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ContactUS $contactUS)
    {
        //
    }

    public function destroy(string $id)
    {
        $item = ContactUS::findOrFail($id);
        $item->delete();
        return response()->json(['success' => 'Contact Us Deleted Successfully']);
    }
}
