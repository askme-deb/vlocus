<style>
    /* Card */
.card{
    border:none;
    border-radius:18px;
    overflow:hidden;
    background:#fff;
    box-shadow:0 10px 35px rgba(15,23,42,.08);
}

.card-body{
    padding:25px;
}

/* DataTable Top Area */
.dataTables_wrapper .row:first-child{
    margin-bottom:20px;
    align-items:center;
}

/* Export Buttons */
.dt-buttons{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
}

.dt-buttons .btn{
    border-radius:12px !important;
    padding:8px 18px;
    font-size:14px;
    font-weight:600;
    border:1px solid #dbe4f0;
    background:#fff;
    color:#475569;
    transition:.3s;
}

.dt-buttons .btn:hover{
    background:#2563eb;
    color:#fff;
    border-color:#2563eb;
    transform:translateY(-2px);
    box-shadow:0 8px 18px rgba(37,99,235,.25);
}

/* Search Box */
.dataTables_filter{
    text-align:right;
}

.dataTables_filter label{
    font-weight:600;
    color:#64748b;
}

.dataTables_filter input{
    width:280px !important;
    height:45px;
    margin-left:10px;
    border-radius:12px;
    border:1px solid #dbe4f0;
    background:#f8fafc;
    padding:0 15px;
    transition:.3s;
}

.dataTables_filter input:focus{
    border-color:#2563eb;
    box-shadow:0 0 0 4px rgba(37,99,235,.15);
}

/* Table */
.table{
    margin-top:15px;
    border-collapse:separate;
    border-spacing:0;
}

.table thead th{
    background:#2563eb;
    color:#fff;
    font-weight:600;
    padding:16px;
    border:none;
    white-space:nowrap;
}

.table thead th:first-child{
    border-radius:12px 0 0 0;
}

.table thead th:last-child{
    border-radius:0 12px 0 0;
}

.table tbody td{
    padding:16px;
    vertical-align:middle;
    border-color:#edf2f7;
}

.table-striped tbody tr:nth-of-type(odd){
    background:#fbfcfe;
}

.table tbody tr{
    transition:.3s;
}

.table tbody tr:hover{
    background:#eef5ff;
    transform:scale(1.002);
}

/* Action Buttons */
td.d-flex{
    gap:10px;
    justify-content:center;
}

td.d-flex .btn{
    width:40px;
    height:40px;
    border-radius:10px;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:0;
    background:#fff;
    border:1px solid #e2e8f0;
    transition:.3s;
}

td.d-flex .btn:hover{
    transform:translateY(-3px);
}

td.d-flex .btn:first-child:hover{
    background:#e8f3ff;
    border-color:#2563eb;
}

td.d-flex .btn:last-child:hover{
    background:#ffecec;
    border-color:#ef4444;
}

/* Pagination */
.dataTables_paginate{
    margin-top:20px;
}

.pagination{
    gap:8px;
}

.pagination .page-link{
    border:none;
    border-radius:10px;
    color:#475569;
    background:#f8fafc;
    padding:10px 16px;
    transition:.3s;
}

.pagination .page-item.active .page-link{
    background:#2563eb;
    color:#fff;
    box-shadow:0 8px 18px rgba(37,99,235,.25);
}

.pagination .page-link:hover{
    background:#2563eb;
    color:#fff;
}

/* Info Text */
.dataTables_info{
    color:#64748b;
    font-weight:500;
    padding-top:15px;
}

/* Responsive */
.table-responsive{
    border-radius:16px;
    overflow:hidden;
}

/* Scrollbar */
.table-responsive::-webkit-scrollbar{
    height:8px;
}

.table-responsive::-webkit-scrollbar-thumb{
    background:#cbd5e1;
    border-radius:20px;
}
.badge-online{
    background:#dcfce7;
    color:#16a34a;
    padding:6px 14px;
    border-radius:50px;
    font-weight:600;
}

.badge-offline{
    background:#fee2e2;
    color:#dc2626;
    padding:6px 14px;
    border-radius:50px;
    font-weight:600;
}

.badge-idle{
    background:#fef3c7;
    color:#d97706;
    padding:6px 14px;
    border-radius:50px;
    font-weight:600;
}




/* ========================================================================== */
/* MOBILE RESPONSIVE FIXES FOR DATA TABLES                                    */
/* ========================================================================== */

@media (max-width: 991.98px) {
    /* Main container spacing adjustments */
    main.main-wrapper {
        margin-top: 60px !important;
        padding: 10px !important;
    }

    .main-content {
        padding: 0 !important;
    }

    /* DataTable Top Control Area (Buttons and Search Input) Stacking */
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

    /* Export buttons full width stretching */
    .dt-buttons {
        display: flex !important;
        justify-content: stretch !important;
        gap: 6px !important;
    }

    .dt-buttons .btn {
        flex: 1;
        padding: 8px 10px !important;
        font-size: 12px !important;
        text-align: center;
        border-radius: 8px !important;
    }

    /* Search Input Form Field Expansion */
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

    /* Table Container Styling & Smooth Horizontal Scrolling */
    .table-responsive {
        width: 100% !important;
        overflow-x: auto !important;
        -webkit-overflow-scrolling: touch;
        border-radius: 12px;
        border: 1px solid #edf2f7;
    }

    /* DataTable Bottom Section (Info & Pagination Alignment) */
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
    /* Padding adjustments for compact mobile view */
    .card-body {
        padding: 15px !important;
    }

    /* Breadcrumbs scale down */
    .breadcrumb-title {
        font-size: 18px !important;
    }
}


</style>
@extends('layouts.app')

@section('title')
    SOS Alrets
@endsection

@section('content')
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">SOS Alrets</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">
                            <i class="bx bx-home-alt"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">SOS Alrets</li>
                </ol>
            </nav>
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
                                <th>Driver</th>
                                <th>Phone</th>
                                <th>Message</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
              
                                @can('SOS Alert Delete')
                                    <th>Action</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @if ($sos_alerts->isNotEmpty())
                                @php
                                    $i = 1;
                                @endphp

                                @foreach ($sos_alerts as $item)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $item->driver->name }}</td>
                                        <td>{{ $item->driver->phone  }}</td>
                                        <td>{{ $item->message }}</td>
                                        <td>{{ $item->latitude  }}</td>
                                        <td>{{ $item->longitude  }}</td>


                            
                                        @can('SOS Alert Delete')
                                            <td class="d-flex">
                                                <a class="btn" href="javascript:void(0);" onclick="deleteItem(this)"
                                                    data-url="{{ route('sos_alert.delete', $item->id) }}" data-item="SOS Alert"
                                                    alt="delete"><i class="text-danger" data-feather="trash-2"></i></a>
                                            </td>
                                        @endcan
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
