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
                        <li class="breadcrumb-item active" aria-current="page">Roles</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                @can('Role Create')
                <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                    <button class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><i class="bi bi-plus-lg me-2"></i>Add New Role</button>
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
                                <th>Name</th>
                                @can('Asign Permission')
                                <th>Asign Permission to Role</th>
                                @endcan
                                @canany(['Role Edit','Role Delete'])
                                <th>Action</th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $role)
                            <tr>
                                <td>{{ $role->name }}</td>
                                @can('Asign Permission')
                                <td><a href="{{ route('role.addPermissionToRole', ['roleId' => $role->id]) }}" class="btn btn-outline-success">Asign Permission</a></td>
                                @endcan
                                @canany(['Role Edit','Role Delete'])
                                <td class="d-flex">
                                    @can('Role Edit')
                                    <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#staticBackdropedit{{ $role->id }}" style="margin-right: 10px;"> 
                                        <i class="text-primary" data-feather="edit"></i>
                                    </button>
                                    @endcan
                                    @can('Role Delete')
                                    <a class="btn" href="javascript:void(0);" onclick="deleteItem(this)"
                                                    data-url="{{ route('role.destroy', ['roleId' => $role->id]) }}" data-item="Route"
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


    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom-0 py-2">
                    <h5 class="modal-title">Add New Role</h5>
                    <a href="javascript:;" class="primaery-menu-close" data-bs-dismiss="modal">
                        <i class="material-icons-outlined">close</i>
                    </a>
                </div>
                <form action="{{ route('role.create') }}" method="post">
                @csrf

                <div class="modal-body">
                    <div class="position-relative">
                        <label for="role-name" class="form-label">Role Name</label>
                        <input type="text" name="name" class="form-control" id="role-name" placeholder="Write Role name here..." required="">
                        <div class="valid-tooltip">
                            Looks good!
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top-0">
                    <button type="submit" class="btn btn-grd-info">Save Role</button>
                </div>                                          
                </form>
            </div>
        </div>
    </div>

    @foreach($roles as $role)
    <div class="modal fade" id="staticBackdropedit{{ $role->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Add New Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('role.update', ['roleId' => $role->id]) }}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="position-relative">
                        <label for="role-name" class="form-label">Role Name</label>
                        <input type="text" name="name" class="form-control" id="role-name" placeholder="Write role name here..." value="{{ $role->name }}" required="">
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
        </div>
    </div>
    @endforeach

    @endsection