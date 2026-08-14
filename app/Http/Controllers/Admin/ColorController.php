<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
class ColorController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('permission:Color Show', only: ['index','show']),
            new Middleware('permission:Color Create', only: ['create','store']),
            new Middleware('permission:Color Edit', only: ['edit','update']),
            new Middleware('permission:Color Delete', only: ['destroy']),
        ];
    }
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $colors = Color::latest()->get();
        return view('admin.color.index',compact('colors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
        return view('admin.color.create');
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
            $data= Color::create([
                'name'=>$request->name,
                'description'=>$request->description,
                'is_visible'=>$request->is_visible,
            ]);


            if($data->id){
                return redirect()->route('color.index')->with(['success'=>'Color Created Successfully']);
            }else{
                return back()->with(['error'=>'Color Type Created']);
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
        
        $data =  Color::findOrFail($id);
        return view('admin.color.edit',compact('data'));
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
                $color =  Color::findOrFail($request->id);
                $color->name = $request->name ;
                $color->description = $request->description ;
                $color->is_visible = $request->is_visible;
    
                if($color->save()){
                    return back()->with(['success'=>'Color Update Successfully']);
                }else{
                    return back()->with(['error'=>'Color Not Update']);
                }
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        
        $color = Color::findOrFail($id);
        if (!$color) {
            return redirect()->back()->withErrors(['error' => 'Color not found.'])->withInput();
        }
        $color->delete();
        return response()->json(['success' => 'Color Deleted Successfully']);
    }
}
