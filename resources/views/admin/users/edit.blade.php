@extends('layouts.app')

    @section('title') System User @endsection
    
    @section('content')

        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">System User</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}">
                                    <i class="bx bx-home-alt"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Edit System User</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                        <a class="btn btn-primary px-4" href="{{ route('system-user.index') }}"><i class="fadeIn animated bx bx-arrow-back"></i>Back</a>
                    </div>
                </div>
            </div>
        <!--end breadcrumb-->

        <form class="needs-validation" action="{{ route('system-user.update',$user->id) }}" method="post" novalidate enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-9">
                    <div class="card mt-4">
                        <div class="card-header text-center">Basic Information</div>
                        <div class="card-body">
                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label">First Name <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" placeholder="Enter First name" name="first_name" value="{{ $user->first_name }}" required>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label">Last Name <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" placeholder="Enter Last name" name="last_name" value="{{ $user->last_name }}" required>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label">Email <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input type="email" class="form-control" name="email" id="email" value="{{ $user->email }}" required>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label">Gender <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <select class="form-control input-height" name="gender" required>
                                        <option value selected disabled>Select...</option>
                                        <option value="male" @if($user->gender == 'male') selected @endif>Male</option>
                                        <option value="female" @if($user->gender == 'female') selected @endif>Female</option>
                                        <option value="others" @if($user->gender == 'others') selected @endif>Others</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label">Mobile No. <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="phone" value="{{ $user->phone }}" required>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label">Alternative Mobile No. <span class="text-danger"></span></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="opt_mobile_no" value="{{ $user->opt_mobile_no }}">
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label">Date Of Birth  <span class="text-danger"></span></label>
                                <div class="col-md-9">
                                    <input type="date" class="form-control" name="date_of_birth" value="{{ $user->date_of_birth }}">
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
                                        <img class="img-thumbnail rounded me-2" id="blah" alt="" width="200" src="{{ $user->getFirstMediaUrl('system-user-image') }}" data-holder-rendered="true" style="display: {{ is_have_image($user->getFirstMediaUrl('system-user-image')) }};">
                                    </div>
                                    <div class="mb-0">
                                        <input class="form-control" name="profile_image" type="file" id="imgInp">
                                    </div> 
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label">Aadhar Card Number <span class="text-danger"></span></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="aadhaar_number" value="{{ $user->aadhar_card_number }}">
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label">Aadhar Card Image</label>
                                <div class="col-md-9">
                                    <div class="mb-3">
                                        <img class="img-thumbnail rounded me-2" id="blah2" alt="" width="200" src="{{ $user->getFirstMediaUrl('system-user-aadhar') }}" data-holder-rendered="true" style="display: {{ is_have_image($user->getFirstMediaUrl('system-user-aadhar')) }};">
                                    </div>
                                    <div class="mb-0">
                                        <input class="form-control" name="aadhar_image" type="file" id="imgInp2">
                                    </div> 
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label">Pan Card Number <span class="text-danger"></span></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="pan_card_number" value="{{ $user->pan_card_number }}">
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label">Pan Card Image</label>
                                <div class="col-md-9">
                                    <div class="mb-3">
                                        <img class="img-thumbnail rounded me-2" id="blah3" alt="" width="200" src="{{ $user->getFirstMediaUrl('system-user-pan') }}" data-holder-rendered="true" style="display: {{ is_have_image($user->getFirstMediaUrl('system-user-pan')) }};">
                                    </div>
                                    <div class="mb-0">
                                        <input class="form-control" name="pan_image" type="file" id="imgInp3">
                                    </div> 
                                </div>
                            </div>
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
                                            <input type="text" id="login-email" class="form-control" value="{{ $user->email }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 mb-3">
                                        <div class="form-group">
                                            <label class="col-form-label">Password <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="password" value="{{ old('password') }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-12 mb-3">
                                        <div class="form-group">
                                            <label class="col-form-label">Choose User Role <span class="text-danger">*</span></label>
                                            <select class="form-control input-height" name="roles" required>
                                                <option value selected disabled>Select...</option>
                                                @foreach($roles as $role)
                                                <option value="{{ $role->name }}" @if($user->getRoleNames()->first() == $role->name) selected @endif>{{ $role->name }}</option>
                                                @endforeach
                                            </select>
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
                                        <input type="radio" id="customRadioInline1" name="status" class="form-check-input" value="1" {{ check_uncheck($user->status,1) }}>
                                        <label class="form-check-label" for="customRadioInline1">Active</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" id="customRadioInline2" name="status" class="form-check-input" value="0" {{ check_uncheck($user->status,0) }}>
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
        $('#email').on('keyup',function(){
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
    </script>
    @endsection