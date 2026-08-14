<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Models;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Models\Brand;
use Illuminate\Support\Facades\Validator;

class ModelsController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('permission:Model Show', only: ['index','show']),
            new Middleware('permission:Model Create', only: ['create','store']),
            new Middleware('permission:Model Edit', only: ['edit','update']),
            new Middleware('permission:Model Delete', only: ['destroy']),
        ];
    }
        /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $models = Models::latest()->get();
        return view('admin.model.index',compact('models'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
        $brands = Brand::where('is_visible',1)->get();
        return view('admin.model.create',compact('brands'));
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
            $data= Models::create([
                'name'=>$request->name,
                'brand_id'=>$request->brand_id,
                'description'=>$request->description,
                'is_visible'=>$request->is_visible,
            ]);


            if($data->id){
                return redirect()->route('model.index')->with(['success'=>'Model Created Successfully']);
            }else{
                return back()->with(['error'=>'Model Type Created']);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Models $models)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        
        $data =  Models::findOrFail($id);
        $brands = Brand::where('is_visible',1)->get();

        return view('admin.model.edit',compact('data','brands'));
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
                $model =  Models::findOrFail($request->id);
                $model->name = $request->name ;
                $model->brand_id = $request->brand_id ;
                $model->description = $request->description ;
                $model->is_visible = $request->is_visible;
    
                if($model->save()){
                    return back()->with(['success'=>'Model Update Successfully']);
                }else{
                    return back()->with(['error'=>'Model Not Update']);
                }
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        
        $model = Models::findOrFail($id);
        if (!$model) {
            return redirect()->back()->withErrors(['error' => 'Model not found.'])->withInput();
        }
        $model->delete();
        return response()->json(['success' => 'Model Deleted Successfully']);
    }
}