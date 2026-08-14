
<x-guest-layout>
    @section('title','Login')
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="row g-3">
        @csrf

        <!-- Email Address -->
        <div class="col-12">
            <x-input-label for="email" class="form-label" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full form-control" placeholder="Enter email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
        </div>

        <!-- Password -->
        <div class="col-12">
            <x-input-label for="password" class="form-label" :value="__('Password')" />
            <div class="input-group" id="show_hide_password">
            <x-text-input id="password" class="form-control border-end-0"
                            type="password"
                            name="password"
                            placeholder="Password"
                            required autocomplete="current-password" />
            <a href="javascript:;" class="input-group-text bg-transparent"><i class="bi bi-eye-slash-fill"></i></a>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
        </div>

        <div class="col-md-6">
            <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" name="remember">
            <label class="form-check-label" for="flexSwitchCheckChecked">Remember Me</label>
            </div>
        </div>
        @if (Route::has('password.request'))
        {{-- <div class="col-md-6 text-end"> 
            <a href="{{ route('password.request') }}">Forgot Password ?</a>
        </div> --}}
        @endif
        <div class="col-12">
            <div class="d-grid">
                <button type="submit" class="btn btn-grd-primary">Login</button>
            </div>
        </div>
        {{-- <div class="col-12">
            <div class="text-start">
                <p class="mb-0">Don't have an account yet? <a href="{{ route('register') }}">Sign up here</a>
                </p>
            </div>
        </div> --}}
    </form>
</x-guest-layout>