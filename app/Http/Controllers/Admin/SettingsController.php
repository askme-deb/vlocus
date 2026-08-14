<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SettingsController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Settings Show', only: ['index', 'create', 'show']),
            new Middleware('permission:Settings Edit', only: ['store', 'edit', 'update']),
            new Middleware('permission:Settings Delete', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setting = Settings::first();
        return view('admin.settings.settings',compact('setting'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Settings $settings)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Settings $settings)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
       $setting = Settings::firstOrNew(['id' => 1]);
       $setting->site_name = $request->site_name;
       $setting->description = $request->description;
       $setting->contact_email = $request->contact_email;
       $setting->contact_phone = $request->contact_phone;
       $setting->address = $request->address;
       $setting->facebook_link = $request->facebook_link;
       $setting->twitter_link = $request->twitter_link;
       $setting->instagram_link = $request->instagram_link;
       $setting->meta_title = $request->meta_title;
       $setting->meta_description = $request->meta_description;
       $setting->cab_search_radius = $request->cab_search_radius;
       $setting->nearby_search_radius = $request->nearby_search_radius;

        if ($request->hasFile('logo')) {
            $setting->clearMediaCollection('logo');
            $setting->addMedia($request->file('logo'))->toMediaCollection('logo');
        }

        if ($request->hasFile('favicon')) {
            $setting->clearMediaCollection('favicon');
            $setting->addMedia($request->file('favicon'))->toMediaCollection('favicon');
        }

 
       $setting->save();

        return redirect()->back()->with('success', 'Settings updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Settings $settings)
    {
        //
    }
}
