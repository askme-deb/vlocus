<x-guest-layout>
    @section('title','Register')

    @section('login-title','Register your account')

    @section('style')
    <link rel="stylesheet" href="{{ asset('assets/admin-assets/plugins/dropify/css/dropify.min.css') }}">
    @endsection

    @section('auth_left_style','width: 600px !important;')

    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <!-- Name -->
            <div class="form-group col-md-6">
                <x-input-label for="name" :value="__('Owner Name')" />
                <x-text-input id="name" class="block mt-1 w-full form-control" placeholder="Enter owner name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Business Name -->
            <div class="form-group col-md-6">
                <x-input-label for="name" :value="__('Business Name')" />
                <x-text-input id="name" class="block mt-1 w-full form-control" placeholder="Enter business name" type="text" name="business_name" :value="old('business_name')" required autocomplete="" />
                <x-input-error :messages="$errors->get('business_name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div class="form-group col-md-6">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full form-control" type="email" placeholder="Enter email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Phone Number -->
            <div class="form-group col-md-6">
                <x-input-label for="email" :value="__('Phone')" />
                <x-text-input id="email" class="block mt-1 w-full form-control" type="number" placeholder="Enter phone number" name="phone" :value="old('phone')" required autocomplete="" />
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>

            <div class="form-group col-md-6">
                <x-input-label for="business-category" :value="__('Business Category')" />
                <select class="form-control input-height" name="business_category" id="business-category" required>
                    <option value selected disabled>Select...</option>
                    @foreach($business_categorys as $business_category)
                    <option value="{{ $business_category->id }}">{{ $business_category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-md-6">
                <x-input-label for="trade_license_number" :value="__('Trade License Number')" />
                <x-text-input id="trade_license_number" class="block mt-1 w-full form-control" type="text" placeholder="Enter Trade license number" name="trade_license_number" :value="old('trade_license_number')" autocomplete="" />
                <x-input-error :messages="$errors->get('trade_license_number')" class="mt-2" />
            </div>

            <div class="form-group col-md-12" id="trade_license_image" style="display: none">
                <label class="form-label">Trade License Image</label>
                <input type="file" class="dropify" name="trade_license_image" id="trade_license_image_input">
            </div>
            <!-- Password -->
            <div class="form-group col-md-6">
                <x-input-label for="password" :value="__('Password')" />
    
                <x-text-input id="password" class="block mt-1 w-full form-control"
                                type="password"
                                name="password"
                                required placeholder="Password" autocomplete="new-password" />
    
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
    
            <!-- Confirm Password -->
            <div class="form-group col-md-6">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
    
                <x-text-input id="password_confirmation" class="block mt-1 w-full form-control"
                                type="password"
                                name="password_confirmation" placeholder="Confirm password" required autocomplete="new-password" />
    
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </div>



        {{-- <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div> --}}
        <div class="text-center">
            <button type="submit" class="btn btn-primary btn-block">Create new account</button>
            <div class="text-muted mt-4">Already have account? <a href="{{ route('login') }}">Sign in</a></div>
        </div>
    </form>

    @section('script')
    <script src="{{ asset('assets/admin-assets/plugins/dropify/js/dropify.min.js') }}"></script>
    <script src="{{ asset('assets/admin-assets/page-assets/js/form/dropify.js') }}"></script>
    <script>
        $('#trade_license_number').on('keyup',function(){
            if ($(this).val().trim() !== '') {
                $('#trade_license_image').show(); // Show the image box and make it required
                $('#trade_license_image_input').attr('required',true);
            } else {
                $('#trade_license_image').hide(); // Hide the image box and remove required
                $('#trade_license_image_input').removeAttr('required');
            }
        });
    </script>
    @endsection

</x-guest-layout>

