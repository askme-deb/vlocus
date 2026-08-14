<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use App\Models\Page;
use App\Models\Faq;
use App\Models\VehicleType;
class HomeController extends Controller
{
    public function index()
    {
        // $page = Page::where('slug', 'home')->first();
        // $faqs = Faq::where('is_visible',1)->orderBy('id','desc')->take(4)->get();
   
        $vehicle_types= VehicleType::where('is_visible',1)->get();
        return view('frontend.index',compact('vehicle_types'));
    }

    public function term_condition()
    {
        $page = Page::where('slug', 'terms-condition')->first();
        return view('frontend.terms_condition',compact('page'));
    }
    public function privacy()
    {
        $page = Page::where('slug', 'privacy-policy')->first();
        return view('frontend.privacy',compact('page'));
    }

    public function company_registration()
    {
        return view('frontend.company_registration');
    }

    public function company_registration_submit(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string',
            'email' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required',
        ]);        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        $company=Company::create([
            'name'=>$request->company_name,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'address'=>$request->address,
            'trade_license'=>$request->registration_number
        ]);

        if ($company) {
            return redirect()->route('home.index')->with(['success'=>'Registartions Successfully']);
        }

    }
}
