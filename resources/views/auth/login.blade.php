<style>
  /* ==========================================================
     70% CUSTOM BACKGROUND IMAGE & 30% CENTERED LIGHT FORM (ONLY CSS)
     ========================================================== */

  body {
    background-image: none !important;
    background-color: #ffffff !important;
    padding: 0 !important;
    margin: 0 !important;
    min-height: 100vh;
    display: flex;
    align-items: stretch;
    justify-content: stretch;
    font-family: 'Noto Sans', sans-serif !important;
    overflow-x: hidden;
  }

  /* Full screen wrapper container */
  .mx-3.mx-lg-0 {
    width: 100% !important;
    max-width: 100% !important;
    margin: 0 !important;
    padding: 0 !important;
    display: flex;
  }

  /* Main card layout setup */
  .card.my-5 {
    width: 100% !important;
    max-width: 100% !important;
    margin: 0 !important;
    padding: 0 !important;
    border-radius: 0 !important;
    background: transparent !important;
    box-shadow: none !important;
    border: none !important;
  }

  .card .row.g-4 {
    display: flex;
    flex-wrap: nowrap;
    height: 100vh;
    margin: 0 !important;
    width: 100% !important;
  }

  /* LEFT SIDE: EXACT 70% WIDTH (Using your exact background image) */
  .card .row.g-4::before {
    content: "";
    flex: 0 0 70%; 
    max-width: 70%;
    background-image: url('https://app.vlocus.in/public/assets/dashboard-assets/assets/images/auth/bg.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    box-sizing: border-box;
  }

  /* RIGHT SIDE: EXACT 30% WIDTH (Clean White Background, Form Centered) */
  .card .row.g-4 > .col-lg-12 {
    flex: 0 0 30%; 
    max-width: 30%;
    background: #ffffff !important;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 30px !important;
    height: 100vh;
    box-sizing: border-box;
  }

  .card-body {
    width: 100% !important;
    max-width: 340px; /* Form box width fixed to stay neat and centered */
    padding: 0 !important;
    margin: 0 auto;
    text-align: left;
  }

  /* Text and Form Styling for the 30% White Side */
  .card-body h4.fw-bold {
    color: #111827 !important;
    font-size: 1.6rem !important;
    font-weight: 700 !important;
    margin-bottom: 6px;
  }

  .card-body p.mb-0 {
    color: #6b7280 !important;
    font-size: 0.9rem !important;
    margin-bottom: 25px !important;
  }

  .form-label {
    color: #374151 !important;
    font-size: 0.85rem !important;
    font-weight: 600 !important;
  }

  .form-control {
    background: #f9fafb !important;
    border: 1px solid #d1d5db !important;
    border-radius: 8px !important;
    color: #111827 !important;
    height: 44px !important;
    padding: 8px 14px !important;
    font-size: 0.9rem !important;
  }

  .form-control:focus {
    background: #ffffff !important;
    border-color: #10b981 !important;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15) !important;
    color: #111827 !important;
  }

  .form-control::placeholder {
    color: #9ca3af !important;
    opacity: 1 !important;
  }

  .input-group-text {
    background: #f9fafb !important;
    border: 1px solid #d1d5db !important;
    border-left: none !important;
    border-radius: 0 8px 8px 0 !important;
    color: #6b7280 !important;
  }

  /* Links & Checkbox colors */
  a {
    color: #10b981 !important;
    font-size: 0.85rem;
    font-weight: 500;
  }
  a:hover {
    color: #059669 !important;
  }

  .form-check-label {
    color: #4b5563 !important;
    font-size: 0.85rem;
  }
  
  .form-check-input:checked {
    background-color: #10b981 !important;
    border-color: #10b981 !important;
  }

  /* Submit Button (Green Theme) */
  .btn-primary {
    background: #10b981 !important;
    border: none !important;
    border-radius: 8px !important;
    color: #ffffff !important;
    font-weight: 600 !important;
    height: 44px !important;
    box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2);
    transition: background 0.2s ease;
  }

  .btn-primary:hover {
    background: #059669 !important;
  }

  /* Mobile & Tablet Responsive View */
  @media (max-width: 991px) {
    body {
      overflow-y: auto !important;
    }

    .card .row.g-4 {
      flex-direction: column;
      height: auto;
    }

    .card .row.g-4::before {
      flex: 0 0 auto;
      max-width: 100%;
      height: 240px;
      width: 100%;
    }

    .card .row.g-4 > .col-lg-12 {
      flex: 0 0 auto;
      max-width: 100%;
      height: auto;
      padding: 30px 20px !important;
    }

    .card-body {
      max-width: 100% !important;
    }
  }
</style>



<x-guest-layout>
    @section('title','Login')
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="row g-3">
        @csrf

        <!-- Email Address -->
        <div class="col-12">
            {{-- <x-input-label for="email" class="form-label" :value="__('Email')" /> --}}
            <x-text-input id="email" class="block mt-1 w-full form-control" placeholder="Enter email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
        </div>

        <!-- Password -->
        <div class="col-12">
            {{-- <x-input-label for="password" class="form-label" :value="__('Password')" /> --}}
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
        <div class="col-md-6 text-end"> 
            <a href="{{ route('password.request') }}">Forgot Password ?</a>
        </div>
        @endif
        
        <div class="col-12">
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Login</button>
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