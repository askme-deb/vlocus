<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
class BrandController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Brand Show', only: ['index','show']),
            new Middleware('permission:Brand Create', only: ['create','store']),
            new Middleware('permission:Brand Edit', only: ['edit','update']),
            new Middleware('permission:Brand Delete', only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $brands = Brand::latest()->get();
        return view('admin.brand.index',compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
        return view('admin.brand.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->isMethod('post')) {
        
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
            ]);        
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            $data= Brand::create([
                'name'=>$request->name,
                'description'=>$request->description,
                'is_visible'=>$request->is_visible,
            ]);


            if($data->id){
                return redirect()->route('brand.index')->with(['success'=>'Brand Created Successfully']);
            }else{
                return back()->with(['error'=>'Brand Type Created']);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        
        $data =  Brand::findOrFail($id);
        return view('admin.brand.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        if ($request->isMethod('post')) {
        
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
            ]);         
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            if ($request->id > 0) {
                $brand =  Brand::findOrFail($request->id);
                $brand->name = $request->name ;
                $brand->description = $request->description ;
                $brand->is_visible = $request->is_visible;
    
                if($brand->save()){
                    return back()->with(['success'=>'Brand Update Successfully']);
                }else{
                    return back()->with(['error'=>'Brand Not Update']);
                }
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        
        $brand = Brand::findOrFail($id);
        if (!$brand) {
            return redirect()->back()->withErrors(['error' => 'Brand not found.'])->withInput();
        }
        $brand->delete();
        return response()->json(['success' => 'Brand Deleted Successfully']);
    }
}
