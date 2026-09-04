@extends('layouts.app')

@section('title')
    Driver KYC
@endsection

@section('content')

    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Driver</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('driver.index') }}">Driver</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Driver KYC</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                <a class="btn btn-primary px-4" href="{{ route('driver.index') }}"><i
                        class="fadeIn animated bx bx-arrow-back"></i>Back</a>
            </div>
        </div>
    </div>
    <!--end breadcrumb-->

    <div class="alert alert-info">
        Driving licence details for <strong>{{ $driver->holder_name ?? $driver->user->name }}</strong> were saved.
        Finish onboarding by adding contact details and verifying Aadhaar/PAN below.
    </div>

    <form class="needs-validation" action="{{ route('driver.kyc.update', $driver->id) }}" method="post" novalidate>
        @csrf
        <div class="row">
            <div class="col-md-12">

                <div class="card mt-4">
                    <div class="card-header text-center">Contact Details</div>
                    <div class="card-body">
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Email <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="email" class="form-control" name="email" id="email"
                                    value="{{ old('email', $driver->user->email) }}" required>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Mobile No. <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="phone"
                                    value="{{ old('phone', $driver->user->phone) }}" required>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Alternative Mobile No.</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="opt_mobile_no"
                                    value="{{ old('opt_mobile_no', $driver->user->opt_mobile_no) }}">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Status</label>
                            <div class="col-md-9">
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="customRadioInline1" name="status" class="form-check-input"
                                        value="1" {{ old('status', (string) $driver->user->status) == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="customRadioInline1">Active</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="customRadioInline2" name="status" class="form-check-input"
                                        value="0" {{ old('status', (string) $driver->user->status) == '0' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="customRadioInline2">Inactive</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header text-center">Driver KYC</div>
                    <div class="card-body">
                        <p class="text-muted small mb-3">
                            Verify the driver's Aadhaar (OTP) and PAN through BankU.
                        </p>
                        @include('admin.driver._kyc_fields', ['data' => $driver->user])

                        <div class="d-md-flex d-grid align-items-center gap-3 mt-3">
                            <button type="submit" class="btn btn-grd-primary px-4 text-light">Submit</button>
                            <button type="reset" class="btn btn-grd-info px-4 text-light">Reset</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>

    <style>
        #kycSubmitOverlay {
            position: fixed;
            inset: 0;
            background: rgba(255, 255, 255, .85);
            z-index: 2000;
            display: none;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 1rem;
        }
        #kycSubmitOverlay .spinner-border {
            width: 3rem;
            height: 3rem;
        }
        #kycSubmitOverlay span {
            font-weight: 600;
            color: #475569;
        }
    </style>
    <div id="kycSubmitOverlay">
        <div class="spinner-border text-primary" role="status"></div>
        <span>Saving driver KYC&hellip;</span>
    </div>

@endsection

@section('scripts')
    @include('admin.driver._kyc_scripts')

    <script>
        // Full-page loading state while the (validated) KYC form submits,
        // matching the vehicle create page's Fetch-then-submit UX.
        $('form.needs-validation').on('submit', function (e) {
            if (this.checkValidity() === false) {
                return;
            }
            $('#kycSubmitOverlay').css('display', 'flex');
        });
    </script>
@endsection
