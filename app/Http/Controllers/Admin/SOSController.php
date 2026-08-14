<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\SOSAlert;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SOSController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:SOS Alert Show', only: ['index']),
            new Middleware('permission:SOS Alert Delete', only: ['delete']),
        ];
    }

    public function index()
    {
        $query = SOSAlert::latest();

        if ($companyId = auth()->user()->companyId()) {
            $query->whereHas('driver.driver', function ($q) use ($companyId) {
                $q->where('company_id', $companyId);
            });
        }

        $sos_alerts = $query->get();
        return view('admin.sos_alert.index',compact('sos_alerts'));
    }
    
        public function delete($id)
    {
        
        $sos_alert = SOSAlert::findOrFail($id);
        if (!$sos_alert) {
            return redirect()->back()->withErrors(['error' => 'SOS Alert not found.'])->withInput();
        }
        $sos_alert->delete();
        return response()->json(['success' => 'SOS Alert Deleted Successfully']);
    }
}
