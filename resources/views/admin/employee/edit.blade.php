@extends('layouts.app')

@section('title')
    Employee
@endsection
@section('css')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">
    
@endsection
@section('content')

    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Employee</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">
                            <i class="bx bx-home-alt"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Employee</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            @can('Employee Create')
                <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                    <a class="btn btn-primary px-4" href="{{ route('employee.index') }}"><i
                            class="fadeIn animated bx bx-arrow-back"></i>Back</a>
                </div>
            @endcan
        </div>
    </div>
    <!--end breadcrumb-->

    <form class="needs-validation" action="{{ route('employee.update') }}" method="post" novalidate enctype="multipart/form-data">
        @csrf


        <input type="hidden" id="user_id" name="user_id" value="{{ $user->id }}">
        <div class="row">
            <div class="col-md-9">
                <div class="card mt-4">
                    <div class="card-header text-center">Basic Information</div>
                    <div class="card-body">
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">First Name <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" placeholder="Enter First name" name="first_name"
                                    value="{{ $user->first_name }}" required>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Last Name <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" placeholder="Enter Last name" name="last_name"
                                    value="{{ $user->last_name }}" required>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Email <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="email" class="form-control" name="email" id="email"
                                    value="{{ $user->email }}" required>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Gender <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select class="form-control input-height" name="gender" required>
                                    <option value selected disabled>Select...</option>
                                    <option value="male" @if ($user->gender == 'male') selected @endif>Male</option>
                                    <option value="female" @if ($user->gender == 'female') selected @endif>Female</option>
                                    <option value="others" @if ($user->gender == 'others') selected @endif>Others</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Mobile No. <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="phone" value="{{ $user->phone }}"
                                    required>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Alternative Mobile No. <span
                                    class="text-danger"></span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="opt_mobile_no"
                                    value="{{ $user->opt_mobile_no }}">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Date Of Birth <span class="text-danger"></span></label>
                            <div class="col-md-9">
                                <input type="date" class="form-control" name="date_of_birth"
                                    value="{{ $user->date_of_birth }}">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Address <span class="text-danger"></span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="address" value="{{ $user->address }}">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Profile Picture</label>
                            <div class="col-md-9">
                                <div class="mb-3">
                                    <img class="img-thumbnail rounded me-2" id="blah" alt="" width="200"
                                        src="{{ $user->getFirstMediaUrl('system-user-image') }}"
                                        data-holder-rendered="true"
                                        style="display: {{ is_have_image($user->getFirstMediaUrl('system-user-image')) }};">
                                </div>
                                <div class="mb-0">
                                    <input class="form-control" name="profile_image" type="file" id="imgInp">
                                </div>
                            </div>
                        </div>
                        
                       
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Aadhar Card Number <span
                                    class="text-danger"></span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="aadhaar_number"
                                    value="{{ $user->aadhar_card_number }}">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Aadhar Card Image</label>
                            <div class="col-md-9">
                                <div class="mb-3">
                                    <img class="img-thumbnail rounded me-2" id="blah2" alt="" width="200"
                                        src="{{ $user->getFirstMediaUrl('system-user-aadhar') }}"
                                        data-holder-rendered="true"
                                        style="display: {{ is_have_image($user->getFirstMediaUrl('system-user-aadhar')) }};">
                                </div>
                                <div class="mb-0">
                                    <input class="form-control" name="aadhar_image" type="file" id="imgInp2">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Pan Card Number <span
                                    class="text-danger"></span></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="pan_card_number"
                                    value="{{ $user->pan_card_number }}">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">Pan Card Image</label>
                            <div class="col-md-9">
                                <div class="mb-3">
                                    <img class="img-thumbnail rounded me-2" id="blah3" alt="" width="200"
                                        src="{{ $user->getFirstMediaUrl('system-user-pan') }}"
                                        data-holder-rendered="true"
                                        style="display: {{ is_have_image($user->getFirstMediaUrl('system-user-pan')) }};">
                                </div>
                                <div class="mb-0">
                                    <input class="form-control" name="pan_image" type="file" id="imgInp3">
                                </div>
                            </div>
                        </div>
                        @if(auth()->user()->hasRole('Branch'))
                            {{-- Hidden field for Branch user --}}
                            <input type="hidden" name="branch_id" value="{{ auth()->user()->id }}">
                        @else
                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label">Branch <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <select class="form-control input-height single-select-field" name="branch_id" required>
                                        <option value selected disabled>Select...</option>
                                        @foreach ($branchs as $item)
                                            <option value="{{ $item->id }}"
                                                @if (old('branch_id',$user->employee?->branch?->id) == $item->id) selected @endif>{{ $item->name }} @unless(auth()->user()->hasRole('Company')) | {{ $item->branch?->company?->name ?? 'N/A' }} @endunless</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="row">
                    <div class="card mt-4">
                        <div class="card-header text-center">Account Information</div>
                        <div class="card-body">
                            <div class="row clearfix">
                                <div class="col-sm-12 mb-3">
                                    <div class="form-group">
                                        <label class="col-form-label">Email</label>
                                        <input type="text" id="login-email" class="form-control"
                                            value="{{ $user->email }}" readonly>
                                    </div>
                                </div>
                                <div class="col-sm-12 mb-3">
                                    <div class="form-group">
                                        <label class="col-form-label">Password <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="password"
                                            value="{{ old('password') }}">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header text-center">Publish</div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label mb-3 d-flex">Status</label>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="customRadioInline1" name="status"
                                        class="form-check-input" value="1" {{ check_uncheck($user->status, 1) }}>
                                    <label class="form-check-label" for="customRadioInline1">Active</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="customRadioInline2" name="status"
                                        class="form-check-input" value="0" {{ check_uncheck($user->status, 0) }}>
                                    <label class="form-check-label" for="customRadioInline2">Inactive</label>
                                </div>
                            </div>
                            <div class="d-md-flex d-grid align-items-center gap-3">
                                <button type="submit" class="btn btn-grd-primary px-4 text-light">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection

@section('scripts')
    <script>
        $('#email').on('keyup', function() {
            $('#login-email').val($(this).val());
        });
    </script>
    <script>
        $('#imgInp2').on('change', function() {
            var input = this;
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#blah2').attr('src', e.target.result).css('display', 'block');
                }
                reader.readAsDataURL(input.files[0]);
            }
        });
        $('#imgInp3').on('change', function() {
            var input = this;
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#blah3').attr('src', e.target.result).css('display', 'block');
                }
                reader.readAsDataURL(input.files[0]);
            }
        });
        $('#imgInp4').on('change', function() {
            var input = this;
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#blah4').attr('src', e.target.result).css('display', 'block');
                }
                reader.readAsDataURL(input.files[0]);
            }
        });
        
        
        document.getElementById('generate-password').addEventListener('click', function() {
            const length = 8;
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*';
            let password = '';

            for (let i = 0; i < length; i++) {
                password += chars.charAt(Math.floor(Math.random() * chars.length));
            }

            document.getElementById('generated-password').value = password;
        });
    </script>
       <script>
        $(document).ready(function() {

            $('#companyCreateForm').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    url: '{{ route('driver.add_company') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $('#submitBtn').prop('disabled', true).text('Saving...');
                    },
                    success: function(response) {
                        if (response.success) {
                            round_success_noti(response.message);
                            $('#companyCreateForm')[0].reset();
                            $('#quickCompanyAddModal').modal('hide');

                            let newCompany =
                                `<option value="${response.company.id}">${response.company.name}</option>`;
                            $('#company_id').append(newCompany);
                        } else {
                            round_error_noti('Error: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'An unexpected error occurred!';
                        if (xhr.responseJSON) {
                            if (typeof xhr.responseJSON.message === 'object') {
                                errorMessage = '';
                                $.each(xhr.responseJSON.message, function(key, value) {
                                    errorMessage += value[0] + "<br>";
                                });
                            } else if (typeof xhr.responseJSON.message === 'string') {
                                errorMessage = xhr.responseJSON.message;
                            }
                        }
                        round_error_noti(errorMessage);
                    },
                    complete: function() {
                        $('#submitBtn').prop('disabled', false).text('Submit');
                    }
                });
            });



        });
    </script>
@endsection
