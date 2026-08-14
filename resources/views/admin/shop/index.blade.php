<style>
  /* ==========================================
     ULTIMATE FULL RESPONSIVE FIXES (Only CSS)
     ========================================== */

  @media (max-width: 1199px) {
    /* Tablet & Small Laptop adjustments */
    main.main-wrapper {
      margin-top: 75px !important;
      padding: 12px !important;
    }
  }

  @media (max-width: 991px) {
    /* Navigation & Header Fixes */
    .top-header .navbar {
      padding: 8px 12px !important;
    }
    
    /* DataTable Top Section Stack */
    #example2_wrapper .row:first-child {
      display: flex;
      flex-direction: column;
      gap: 12px;
      align-items: stretch !important;
    }

    #example2_wrapper .col-sm-12.col-md-6 {
      width: 100% !important;
      max-width: 100% !important;
      display: flex;
      justify-content: center;
    }

    /* Search Box Full Width */
    .dataTables_filter {
      text-align: center !important;
      width: 100%;
    }

    .dataTables_filter input {
      width: 100% !important;
      margin-left: 0 !important;
      margin-top: 6px;
    }

    /* Export Buttons Wrapping */
    .dt-buttons {
      display: flex;
      justify-content: center !important;
      gap: 6px;
      width: 100%;
    }

    .dt-buttons .btn {
      padding: 6px 12px;
      font-size: 13px;
    }
  }

  @media (max-width: 767px) {
    /* Mobile Devices */
    main.main-wrapper {
      margin-top: 65px !important;
      padding: 8px !important;
    }

    .card {
      border-radius: 12px !important;
    }

    .card-body {
      padding: 12px !important;
    }

    /* Breadcrumb & Action Button Full Width */
    .page-breadcrumb {
      flex-direction: column;
      align-items: flex-start !important;
      gap: 10px;
    }

    .page-breadcrumb .ms-auto {
      margin-left: 0 !important;
      width: 100%;
    }

    .page-breadcrumb .ms-auto .btn {
      width: 100%;
      justify-content: center;
    }

    /* Table Pagination Center Align */
    #example2_wrapper .row:last-child {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 12px;
      text-align: center;
    }

    #example2_wrapper .col-sm-12.col-md-5,
    #example2_wrapper .col-sm-12.col-md-7 {
      width: 100% !important;
      display: flex;
      justify-content: center;
    }

    .dataTables_info {
      padding-top: 0 !important;
    }
  }

  @media (max-width: 480px) {
    /* Extra Small Mobile Devices */
    .dt-buttons .btn {
      padding: 5px 8px;
      font-size: 11px;
    }

    .table thead th, 
    .table tbody td {
      padding: 10px 8px !important;
      font-size: 0.75rem !important;
    }
  }
  
/* Strict Table Mobile Responsive Fix */
  @media (max-width: 768px) {
    /* 1. Table-ke scrollable container er vitor force kora */
    .table-responsive {
      display: block !important;
      width: 100% !important;
      overflow-x: auto !important;
      -webkit-overflow-scrolling: touch !important;
      white-space: nowrap !important;
    }

    /* 2. DataTable container width issue fix kora */
    #example2_wrapper, 
    #example2_wrapper .row, 
    #example2_wrapper .col-sm-12 {
      width: 100% !important;
      margin: 0 !important;
      padding: 0 !important;
    }

    /* 3. Table cells padding choto kora jate mobile-e neatly fit hoy */
    #example2 th, 
    #example2 td {
      padding: 10px 12px !important;
      font-size: 13px !important;
    }

    /* 4. Action buttons (Edit/Delete) stack ba align thik rakha */
    #example2 td.d-flex {
      display: flex !important;
      gap: 8px !important;
      align-items: center !important;
    }
  }
</style>

@extends('layouts.app')

@section('title') Shop @endsection

@section('content')
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Shop</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">
                            <i class="bx bx-home-alt"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Shop</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            @can('Shop Create')
                <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                    <a class="btn btn-primary px-4" href="{{ route('shop.create') }}"><i class="bi bi-plus-lg me-2"></i>Add New</a>
                </div>
            @endcan
        </div>
    </div>
    <!--end breadcrumb-->

    @canany(['Shop Bulk Upload', 'Shop Bulk Upload Template'])
    <div class="accordion" id="shopBulkAccordion">
        <div class="accordion-item">
            <h2 class="accordion-header" id="shopBulkHeading">
                <button class="accordion-button collapsed" type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#shopBulkCollapse"
                        aria-expanded="false"
                        aria-controls="shopBulkCollapse">
                    <i class="bi bi-info-circle"></i> &nbsp; Bulk Upload Shops from Excel
                </button>
            </h2>

            <div id="shopBulkCollapse"
                 class="accordion-collapse collapse"
                 aria-labelledby="shopBulkHeading"
                 data-bs-parent="#shopBulkAccordion">
                <div class="accordion-body">

                    <div class="alert alert-info mt-3">
                        <ul class="mb-0 ps-3">
                            <li>Download the template below and fill in <strong>Shop Name</strong>, <strong>Shop Address</strong>, <strong>Contact Person Name</strong> and <strong>Contact Person Phone</strong> for each shop.</li>
                            <li><strong>Latitude</strong> and <strong>Longitude</strong> are optional but must be numeric if provided.</li>
                            <li><strong>Status</strong> must be either <code>Active</code> or <code>Inactive</code> (<code>Pending</code> is also accepted and treated as <code>Active</code>).</li>
                            <li><strong>Shop Number</strong> is generated automatically and should not be entered.</li>
                            <li class="text-danger">Do not rename, remove, or reorder any columns in the Excel sheet.</li>
                            <li class="text-danger">Once filled, save the file and upload it here in <strong>.xlsx</strong> format only.</li>
                        </ul>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center" style="border: none;">
                            <h5>Bulk Upload Shops</h5>
                            @can('Shop Bulk Upload Template')
                                <a href="{{ route('shop.template.download') }}" class="btn btn-sm btn-success">
                                    <i class="bi bi-download"></i> Download Template
                                </a>
                            @endcan
                        </div>

                        @can('Shop Bulk Upload')
                        <div class="card-body">
                            <form action="{{ route('shop.bulk.upload') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <input type="file" name="bulk_file" class="form-control" required>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-upload"></i> Upload File
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @endcan
                    </div>

                </div>
            </div>
        </div>
    </div>
    @endcanany

    <div class="card mt-4">
        <div class="card-body">
            <div class="product-table">
                <div class="table-responsive">
                    <table id="example2" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>S.l</th>
                                <th>Shop Number</th>
                                <th>Name</th>
                                <th>Image</th>
                                <th>Address</th>
                                <th>Registration No.</th>
                                <th>Contact Person</th>
                                <th>Contact Number</th>
                                <th>Location Coordinates</th>
                                <th>Status</th>
                                @canany(['Shop Edit', 'Shop Delete'])
                                    <th>Action</th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody>
                            @if ($shops->isNotEmpty())
                                @foreach ($shops as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                     <td>{{ $item->shop_number }}</td>
                                    <td>{{ $item->shop_name }}</td>
                                    <td><img src="{{ $item->getFirstMediaUrl('shop-image') }}" alt="" width="50"></td>
                                    <td>{{ $item->shop_address }}</td>
                                    <td>{{ $item->shop_registration_number }}</td>
                                    <td>{{ $item->shop_contact_person_name }}</td>
                                    <td>{{ $item->shop_contact_person_phone }}</td>
                                    <td>{{ $item->shop_latitude }}, {{ $item->shop_longitude }}</td>
                                    <td>{!! check_status($item->is_visible) !!}</td>

                                    @canany(['Shop Edit', 'Shop Delete'])
                                        <td class="d-flex">
                                            @can('Shop Edit')
                                                <a class="btn" href="{{ route('shop.edit', $item->id) }}" alt="edit"><i
                                                        class="text-primary" data-feather="edit"></i></a>
                                            @endcan
                                            @can('Shop Delete')
                                                <a class="btn" href="javascript:void(0);" onclick="deleteItem(this)"
                                                    data-url="{{ route('shop.destroy',$item->id) }}" data-item="Shop"
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
