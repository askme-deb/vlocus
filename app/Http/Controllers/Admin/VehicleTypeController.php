<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\VehicleType;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class VehicleTypeController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Vehicle Type Show', only: ['index','show']),
            new Middleware('permission:Vehicle Type Create', only: ['create','store']),
            new Middleware('permission:Vehicle Type Edit', only: ['edit','update']),
            new Middleware('permission:Vehicle Type Delete', only: ['destroy']),
        ];
    }

    public function index()
    {
        $vehicle_types = VehicleType::latest()->get();
        return view('admin.vehicle_type.index',compact('vehicle_types'));
    }

    public function create()
    {
        return view('admin.vehicle_type.add');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        $data= VehicleType::create([
            'name'=>$request->name,
            'description'=>$request->description,
            'is_visible'=>$request->is_visible,
            'is_set_price_management' => $request->is_set_price_management ? $request->is_set_price_management : 0,
        ]);

        if ($request->hasFile('image')) {
            $data->addMedia($request->file('image'))->toMediaCollection('vehicle-type-icon');
        }

        $data->update();

        // if($data->is_set_price_management == 1){
        //     $fair = CabFair::create([
        //         'vehicle_type_id'=>$data->id,
        //         'base_fare'=>$request->base_fare,
        //         'per_km_price'=>$request->per_km_price,
        //         'waiting_time_free_minutes' => $request->waiting_time_free_minutes,
        //         'waiting_charge_per_min' => $request->waiting_charge_per_min,
        //         'petrol_price' => $request->petrol_price,
        //         'diesel_price' => $request->diesel_price,
        //         'company_charge_percent' => $request->company_charge_percent
        //     ]);
        // }


        if($data->id){
            return redirect()->route('vehicle-type.index')->with(['success'=>'Vehicle Type Created Successfully']);
        }else{
            return back()->with(['error'=>'Vehicle Type Created']);
        }
    }

    public function show(VehicleType $vehicleType)
    {
        //
    }

    public function edit($id)
    {
        $data = VehicleType::findOrFail($id);
        return view('admin.vehicle_type.edit',compact('data'));
    }

    public function update(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);         
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        if ($request->id > 0) {
            $vehicle_type =  VehicleType::findOrFail($request->id);
            $vehicle_type->name = $request->name ;
            $vehicle_type->description = $request->description;
            $vehicle_type->is_visible = $request->is_visible;
            $vehicle_type->is_set_price_management = $request->is_set_price_management ?? 0;

            if ($request->hasFile('image')) {
                // Optionally clear previous images
                $vehicle_type->clearMediaCollection('vehicle-type-icon');
                $vehicle_type->addMediaFromRequest('image')->toMediaCollection('vehicle-type-icon');
            }

            $res = $vehicle_type->save();

            // if($vehicle_type->is_set_price_management == 1){
            //     $fair = CabFair::updateOrCreate(
            //         ['vehicle_type_id' => $request->id],
            //         [
            //             'base_fare' => $request->base_fare,
            //             'per_km_price' => $request->per_km_price,
            //             'waiting_time_free_minutes' => $request->waiting_time_free_minutes,
            //             'waiting_charge_per_min' => $request->waiting_charge_per_min,
            //             'petrol_price' => $request->petrol_price,
            //             'diesel_price' => $request->diesel_price,
            //             'company_charge_percent' => $request->company_charge_percent,
            //         ]
            //     );
            // }else{
                
            // }
            
 
            if($res){
                return back()->with(['success'=>'Vehicle Type Update Successfully']);
            }else{
                return back()->with(['error'=>'Vehicle Type Not Update']);
            }
        }
    }

    public function destroy(string $id)
    {
        $vehicle_type = VehicleType::findOrFail($id);
        if (!$vehicle_type) {
            return redirect()->back()->withErrors(['error' => 'Vehicle Type not found.'])->withInput();
        }
        $vehicle_type->delete();
        return response()->json(['success' => 'Vehicle Type Deleted Successfully']);

    }
}
