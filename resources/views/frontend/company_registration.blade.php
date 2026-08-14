@extends('frontend.layouts.app')
@section('title', $page->meta_title ?? 'Company Registration')
@section('meta_description', $page->meta_description ?? '')
@section('meta_keywords', $page->meta_keywords ?? '')

@section('css')
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> --}}

@endsection
@section('content')

    <section class="tab-profile py-5">
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow-lg p-4">
                        <h4 class="text-center mb-4">Company Registration</h4>
                        <form id="registerForm" action="{{ route('front.company_registration.submit') }}" method="POST" enctype="multipart/form-data">
                            @csrf
        
                            <div class="mb-3">
                                <label for="company_name" class="form-label">Company Name</label>
                                <input type="text" class="form-control" id="company_name" name="company_name">
                                <span id="company_name_error" class="text-danger"></span>
                            </div>
        
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email">
                                <span id="email_error" class="text-danger"></span>
                            </div>
        
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone">
                                <span id="phone_error" class="text-danger"></span>
                            </div>
        
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="2"></textarea>
                                <span id="address_error" class="text-danger"></span>
                            </div>
        
                            <div class="mb-3">
                                <label for="registration_number" class="form-label">Registration Number</label>
                                <input type="text" class="form-control" id="registration_number" name="registration_number">
                                <span id="registration_number_error" class="text-danger"></span>
                            </div>
        
                            <div class="d-grid">
                                <button type="submit" class="btn btn_style">Register</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


    </section>

@endsection

@section('scripts')


    <script>
        $(document).ready(function() {
            $("#registerForm").on("submit", function(e) {
                let isValid = true;
                let company_name = $("#company_name").val().trim();
                if (company_name === "") {
                    $("#company_name_error").text("Company Name is required.");
                    isValid = false;
                } else {
                    $("#company_name_error").text("");
                }
                let email = $("#email").val().trim();
                let emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                if (email === "") {
                    $("#email_error").text("Email is required.");
                    isValid = false;
                } else if (!emailPattern.test(email)) {
                    $("#email_error").text("Enter a valid email.");
                    isValid = false;
                } else {
                    $("#email_error").text("");
                }

                let phone = $("#phone").val().trim();
                let phonePattern = /^[0-9]{10}$/;
                if (phone === "") {
                    $("#phone_error").text("Phone Number is required.");
                    isValid = false;
                } else if (!phonePattern.test(phone)) {
                    $("#phone_error").text("Enter a valid 10-digit phone number.");
                    isValid = false;
                } else {
                    $("#phone_error").text("");
                }

                let address = $("#address").val().trim();
                if (address === "") {
                    $("#address_error").text("Address is required.");
                    isValid = false;
                } else {
                    $("#address_error").text("");
                }

                let registration_number = $("#registration_number").val().trim();
                if (registration_number === "") {
                    $("#registration_number_error").text("Registration Number is required.");
                    isValid = false;
                } else {
                    $("#registration_number_error").text("");
                }

                if (!isValid) {
                    e.preventDefault();
                }
            });
        });
     
    </script>
@endsection
