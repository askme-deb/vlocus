@extends('layouts.app')

@section('title')
Colors
@endsection

@section('content')
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Colors</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">
                            <i class="bx bx-home-alt"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Colors</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            @can('Color Create')
                <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                    <a class="btn btn-primary px-4" href="{{ route('color.create') }}"><i class="bi bi-plus-lg me-2"></i>Add
                        New</a>
                </div>
            @endcan
        </div>
    </div>
    <!--end breadcrumb-->

    <div class="card mt-4">
        <div class="card-body">
            <div class="product-table">
                <div class="table-responsive">
                    <table id="example2" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>S.l</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Status</th>
                                @canany(['Color Edit', 'Color Delete'])
                                    <th>Action</th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody>
                            @if ($colors->isNotEmpty())
                            @php
                                $i = 1;
                            @endphp
                                
                                @foreach ($colors as $item)
                                <tr>
                                    <td>{{ $i++; }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->description }}</td>

                                    <td>{!! check_status($item->is_visible) !!}</td>
                                    @canany(['Color Edit', 'Color Delete'])
                                        <td class="d-flex">
                                            @can('Color Edit')
                                                <a class="btn" href="{{ route('color.edit', $item->id) }}" alt="edit"><i
                                                        class="text-primary" data-feather="edit"></i></a>
                                            @endcan
                                            @can('Color Delete')
                                                <a class="btn" href="javascript:void(0);" onclick="deleteItem(this)"
                                                    data-url="{{ route('color.delete', $item->id) }}" data-item="Color"
                                                    alt="delete"><i
                                                    class="text-danger" data-feather="trash-2"></i></a>
                                            @endcan
                                        </td>
                                    @endcanany
                                </tr>
                                @endforeach
                            @endif
                           
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
