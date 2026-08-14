<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserBusiness;
use App\Models\BusinessCategory;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $business_categorys = BusinessCategory::where('is_visible',1)->get();
        return view('auth.register',compact('business_categorys'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'business_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'digits:10', 'regex:/^[6789]/', 'unique:'.User::class],
            'business_category' => ['required'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $name = $request->name;
        $nameParts = explode(' ', $name, 2);

        $user = User::create([
            'name' => $request->name,
            'first_name' => $nameParts[0],
            'last_name' => $nameParts[1] ?? '',
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        $user_business = UserBusiness::create([
            'user_id' => $user->id,
            'business_name' => $request->business_name,
            'business_category_id' => $request->business_category,
            'trade_license_number' => $request->trade_license_number,
            'business_address' => $request->business_address
        ]);

        if ($request->hasFile('trade_license_image')) {
            $user->addMedia($request->file('trade_license_image'))->toMediaCollection('user-trade-licence');
        }
        
        $user->user_id = generateUniqueId('user');

        $user->syncRoles('User');
        $user->update();

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
