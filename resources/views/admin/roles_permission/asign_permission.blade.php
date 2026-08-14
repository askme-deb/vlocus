@extends('layouts.app')

    @section('title') Dashboard @endsection
    
    @section('content')

    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Role Permission</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="javascript:;">
                            <i class="bx bx-home-alt"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Assign Permissions</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                <a class="btn btn-primary px-4" href="{{ route('roles') }}"><i class="fadeIn animated bx bx-arrow-back"></i>Back</a>
            </div>
        </div>
    </div>
    <!--end breadcrumb-->

    <div class="card mt-4">
        <div class="card-body">
            <form class="custom-validation" action="{{ route('role.give-permissions', ['roleId' => $role->id]) }}" method="post">
                @csrf
                <div class="">
                    <h4 class="mb-3">Permissions Name</h4>
                    <div class="form-check mb-2">
                        <input 
                            class="form-check-input" 
                            type="checkbox" 
                            id="selectAll" 
                            onclick="toggleSelectAll(this)"
                        />
                        <label class="form-check-label" for="selectAll">
                            Select All
                        </label>
                    </div>
                    @foreach ($permissions as $item)
                    <div class="form-check form-check-inline">
                        <input 
                            class="form-check-input permission-checkbox" 
                            name="permission[]" 
                            id="{{ $item->name}}" 
                            type="checkbox" 
                            value="{{ $item->name}}" 
                            {{ in_array($item->id, $rolePermissions) ? 'checked': '' }}
                        />
                        <label class="form-check-label" for="{{ $item->name}}">{{ $item->name }}</label>
                    </div>
                    @endforeach
                </div>
                <div class="d-flex justify-content-center mt-5">
                    <button type="submit" class="btn text-light btn-grd-info">Save</button>
                </div>
            </form>
        </div>
    </div>
    
    @endsection

    @section('scripts')
    <script>
        function toggleSelectAll(source) {
            const checkboxes = document.querySelectorAll('.permission-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = source.checked;
            });
        }
    </script>
    @endsection