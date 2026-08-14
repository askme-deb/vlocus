@extends('layouts.app')

    @section('title') Users @endsection
    
    @section('content')

    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Users</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">
                                <i class="bx bx-home-alt"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Users</li>
                    </ol>
                </nav>
            </div>
            {{-- <div class="ms-auto">
                @can('User Create')
                <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                    <a class="btn btn-primary px-4" href="{{ route('user.create') }}"><i class="bi bi-plus-lg me-2"></i>Add New Users</a>
                </div>
                @endcan
            </div> --}}
        </div>
    <!--end breadcrumb-->

    <div class="card mt-4">
        <div class="card-body">
            <div class="product-table">
                <div class="table-responsive">
                    <table id="example2" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sl No.</th>
                                {{-- <th>Image</th> --}}
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Registred Date</th>
                                <th>Status</th>
                                @canany(['User Edit','User Delete'])
                                <th>Action</th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                {{-- <td class="w60">
                                    <img class="avatar" width="50" src="{{ $user->getFirstMediaUrl('user-image') }}" alt="">
                                </td> --}}
                                <td><span class="font-16">{{ $user->name }}</span></td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>{{ $user->getRoleNames()->first() }}</td>
                                <td>{{ format_datetime($user->created_at) }}</td>
                                <td>{!! check_status($user->status) !!}</td>
                                <td>
                                    <a href="{{ route('user.show',$user->id) }}" class="btn btn-icon btn-sm" title="View"><i class="text-info" data-feather="eye"></i></a>
                                    {{-- @can('User Edit')
                                    <a href="{{ route('user.edit',$user->id) }}" class="btn btn-icon btn-sm" title="Edit"><i class="text-primary" data-feather="edit"></i></a>
                                    @endcan --}}
                                    @can('User Delete')
                                    <a class="btn" href="javascript:void(0);" onclick="deleteItem(this)"
                                                    data-url="{{ route('user.destroy',$user->id) }}" data-item="Users"
                                                    alt="delete"><i
                                                    class="text-danger" data-feather="trash-2"></i></a>
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @endsection