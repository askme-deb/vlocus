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
                            <a href="{{ route('dashboard') }}">
                                <i class="bx bx-home-alt"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Permissions</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                @can('Permission Create')
                <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                    <button class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><i class="bi bi-plus-lg me-2"></i>Add New Permission</button>
                </div>
                @endcan
            </div>
        </div>
    <!--end breadcrumb-->

    <div class="card mt-4">
        <div class="card-body">
            <div class="product-table">
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Permission Name</th>
                                @canany(['Permission Edit','Permission Delete'])
                                <th>Action</th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($permissions as $permission)
                            <tr>
                                <td>{{ $permission->name }}</td>
                                @canany(['Permission Edit','Permission Delete'])
                                <td class="d-flex">
                                    @can('Permission Edit')
                                    <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#staticBackdropedit{{ $permission->id }}" style="margin-right: 10px;"> 
                                        <i class="text-primary" data-feather="edit"></i>
                                    </button>
                                    @endcan
                                    @can('Permission Delete')
                                    <a class="btn" href="javascript:void(0);" onclick="deleteItem(this)"
                                                    data-url="{{ route('permission.destroy', ['permissionId' => $permission->id]) }}" data-item="Route"
                                                    alt="delete"><i
                                                    class="text-danger" data-feather="trash-2"></i></a>
                                    @endcan
                                </td>
                                @endcanany
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Add New Permission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('permission.create') }}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="position-relative">
                        <label for="permission-name" class="form-label">Permission Name</label>
                        <input type="text" name="name" class="form-control" id="permission-name" placeholder="Write permission name here..." required="">
                        <div class="valid-tooltip">
                            Looks good!
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-info">Save</button>
                </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
    </div>
    
    @foreach($permissions as $permission)
    <div class="modal fade" id="staticBackdropedit{{ $permission->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Edit Permission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('permission.update', ['permissionId' => $permission->id]) }}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="position-relative">
                        <label for="role-name" class="form-label">Permission Name</label>
                        <input type="text" name="name" class="form-control" id="role-name" placeholder="Write Permission name here..." value="{{ $permission->name }}" required="">
                        <div class="valid-tooltip">
                            Looks good!
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-info">Save</button>
                </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
    </div>
    @endforeach


    @endsection