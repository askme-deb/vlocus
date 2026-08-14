<style>
    /* ========================================================================== */
/* MOBILE RESPONSIVE FIXES FOR VEHICLE TYPES TABLE PAGE                       */
/* ========================================================================== */

@media (max-width: 991.98px) {
    /* Main wrapper and content spacing fixes */
    main.main-wrapper {
        margin-top: 60px !important;
        padding: 10px !important;
    }

    .main-content {
        padding: 0 !important;
    }

    /* Header breadcrumb & "Add New" button alignment */
    .page-breadcrumb {
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 12px !important;
    }

    .page-breadcrumb .ms-auto {
        margin-left: 0 !important;
        width: 100% !important;
    }

    .page-breadcrumb .ms-auto .d-flex {
        width: 100% !important;
    }

    .page-breadcrumb .btn {
        width: 100% !important;
        text-align: center !important;
    }

    /* DataTable Top Control Area (Export Buttons and Search Input) Stacking */
    .dataTables_wrapper .row:first-child {
        display: flex !important;
        flex-direction: column !important;
        align-items: stretch !important;
        gap: 12px !important;
        margin-bottom: 15px !important;
    }

    .dataTables_wrapper .row:first-child > div {
        width: 100% !important;
        max-width: 100% !important;
        text-align: left !important;
    }

    /* Export buttons layout optimization */
    .dt-buttons {
        display: flex !important;
        justify-content: stretch !important;
        gap: 6px !important;
    }

    .dt-buttons .btn {
        flex: 1;
        padding: 8px 6px !important;
        font-size: 11px !important;
        text-align: center;
        border-radius: 8px !important;
    }

    /* Search input full width expansion */
    .dataTables_filter {
        text-align: left !important;
    }

    .dataTables_filter label {
        display: flex !important;
        flex-direction: column !important;
        align-items: stretch !important;
        width: 100% !important;
        font-weight: 600;
        color: #64748b;
    }

    .dataTables_filter input {
        width: 100% !important;
        margin-left: 0 !important;
        margin-top: 6px;
        height: 42px !important;
        border-radius: 10px !important;
    }

    /* Table Container Styling & Horizontal Scroll for overflow columns */
    .table-responsive {
        width: 100% !important;
        overflow-x: auto !important;
        -webkit-overflow-scrolling: touch;
        border-radius: 12px;
        border: 1px solid #edf2f7;
    }

    /* DataTable Bottom Section (Info & Pagination Center Alignment) */
    .dataTables_wrapper .row:last-child {
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        gap: 12px !important;
        margin-top: 15px !important;
        text-align: center;
    }

    .dataTables_wrapper .row:last-child > div {
        width: 100% !important;
        max-width: 100% !important;
        display: flex;
        justify-content: center;
    }

    .dataTables_info {
        padding-top: 0 !important;
        text-align: center;
        font-size: 13px;
    }

    .pagination {
        justify-content: center !important;
        gap: 4px !important;
    }
    
    .pagination .page-link {
        padding: 8px 12px !important;
        font-size: 13px !important;
    }
}

@media (max-width: 575.98px) {
    /* Compact padding for very small mobile displays */
    .card-body {
        padding: 15px !important;
    }

    .breadcrumb-title {
        font-size: 18px !important;
    }
}
</style>
@extends('layouts.app')

@section('title')
Vehicle Types
@endsection

@section('content')
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Vehicle Types</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">
                            <i class="bx bx-home-alt"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Vehicle Type</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            @can('Vehicle Type Create')
                <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                    <a class="btn btn-primary px-4" href="{{ route('vehicle-type.create') }}"><i class="bi bi-plus-lg me-2"></i>Add
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
                                <th>Icon</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Status</th>
                                @canany(['Vehicle Type Edit', 'Vehicle Type Delete'])
                                    <th>Action</th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody>
                            @if ($vehicle_types->isNotEmpty())
                            @php
                                $i = 1;
                            @endphp
                                
                                @foreach ($vehicle_types as $item)
                                <tr>
                                    <td>{{ $i++; }}</td>
                                    <td><img src="{{ $item->getFirstMediaUrl('vehicle-type-icon') }}" alt="" width="50"></td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->description }}</td>

                                    <td>{!! check_status($item->is_visible) !!}</td>
                                    @canany(['Vehicle Type Edit', 'Vehicle Type Delete'])
                                        <td class="d-flex">
                                            @can('Vehicle Type Edit')
                                                <a class="btn" href="{{ route('vehicle-type.edit', $item->id) }}" alt="edit"><i
                                                        class="text-primary" data-feather="edit"></i></a>
                                            @endcan
                                            @can('Vehicle Type Delete')
                                                <a class="btn" href="javascript:void(0);" onclick="deleteItem(this)"
                                                    data-url="{{ route('vehicle-type.delete', $item->id) }}" data-item="Route"
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
