@extends('layouts.app')

@section('title')
    Bookings 
@endsection

@section('content')
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Bookings</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">
                            <i class="bx bx-home-alt"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Bookings</li>
                </ol>
            </nav>
        </div>
        
    </div>
    <!--end breadcrumb-->

    <div class="card mt-4">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Vehicle Bookings</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="example2" class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Trip</th>
                            <th>Booking Number</th>
                            <th>Seats</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Status</th>
                            @canany(['Vehicle Edit', 'Vehicle Delete'])
                                <th>Action</th>
                            @endcanany
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bookings as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->user->name ?? 'N/A' }}</td>
                                <td>{{ $item->fromStop->name ?? 'N/A' }} → {{ $item->toStop->name ?? 'N/A' }}</td>
                                <td>{{ $item->booking_number }}</td>
                                <td>
                                    {{getBookedSeats($item->id, true)}}
                                </td>
                                <td>{{ number_format($item->total_amount, 2) }}</td>
                                <td>{{ format_datetime($item->created_at) }}</td>
                                <td>
                                    <span class="badge 
                                        {{ $item->status === 'confirm' ? 'bg-success' : 'bg-warning' }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                            
                                <td class="d-flex">
                                    @can('Vehicle Edit')
                                        <a class="btn btn-sm btn-outline-primary me-2" 
                                            href="{{ route('home.print.ticket', $item->id) }}" title="Edit" target="_blank">
                                            Print Ticket
                                        </a>
                                    @endcan
                                    
                                </td>
                               
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">No bookings found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
@endsection
