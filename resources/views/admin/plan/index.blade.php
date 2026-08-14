<style>
  /* Mobile Responsive Fixes */
  @media (max-width: 768px) {
    /* Columns guloake vertically stack korar jonno */
    .row > .col-md-6 {
      flex: 0 0 100% !important;
      max-width: 100% !important;
      border-right: none !important;
      border-bottom: 1px solid #dee2e6;
      padding-bottom: 20px;
      margin-bottom: 20px;
    }

    /* Card body padding mobile-e komano */
    .card-body {
      padding: 0.75rem !important;
    }

    /* Form input fields gulo full width rakhar jonno */
    .form-control, .form-select {
      font-size: 16px !important; /* Mobile Zoom prevent korar jonno */
    }

    /* Table responsive scrollbar thik rakha */
    .table-responsive {
      max-height: 220px !important;
      overflow-x: auto !important;
    }

    /* Action buttons gulo mobile-e thikvabe adjust korar jonno */
    .table td .btn {
      padding: 0.25rem 0.4rem;
      font-size: 0.75rem;
    }
  }
</style>



@extends('layouts.app')
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection
@section('title', 'Plan Management')

@section('content')
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Plans</div>
    <div class="ps-3">
        <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="bx bx-home-alt"></i></a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Plan Management</li>
        </ol>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row"> <!-- Fixed card height -->
            <!-- Add Plan -->
            <div class="col-md-6 border-end">
                @can('Plan Create')
                <h5>Add Plan</h5>
                <form action="{{ route('plans.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="mb-2 col-md-6">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-2 col-md-6">
                            <label class="form-label">Price (₹)</label>
                            <input type="number" name="price" class="form-control" step="0.01" required>
                        </div>
                        <div class="mb-2 col-md-6">
                            <label class="form-label">Coins</label>
                            <input type="number" name="coins" class="form-control" required>
                        </div>
                        <div class="mb-2 col-md-6">
                            <label class="form-label">Task Coin Cost</label>
                            <input type="number" name="task_coin_cost" class="form-control" required>
                        </div>
                        <div class="mb-3 col-md-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control"></textarea>
                        </div>
                    </div>
                    <button class="btn btn-primary w-100">Add Plan</button>
                </form>
                @endcan

                <hr>
                <h6 class="mt-3">Existing Plans</h6>
                <div class="table-responsive" style="max-height: 200px; overflow-y: auto;">
                    <table class="table table-bordered table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Price (₹)</th>
                                <th>Coins</th>
                                <th>Task Coin Cost</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($plans as $plan)
                                <tr>
                                    <td>{{ $plan->name }}</td>
                                    <td>{{ $plan->price }}</td>
                                    <td>{{ $plan->coins }}</td>
                                    <td>{{ $plan->task_coin_cost }}</td>
                                    <td>
                                        @can('Plan Edit')
                                        <button type="button" class="btn btn-sm btn-outline-primary editPlanBtn" data-id="{{ $plan->id }}">Edit</button>
                                        @endcan
                                        @can('Plan Delete')
                                        <form action="{{ route('plans.destroy', $plan->id) }}" method="POST" style="display:inline-block;">
                                            @csrf @method('DELETE')
                                            <button type="submit" onclick="return confirm('Delete this plan?')" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Add Feature -->
            <div class="col-md-6 d-flex flex-column">
                @can('Plan Create')
                <h5>Add Feature</h5>
                <form action="{{ route('features.store') }}" method="POST">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label">Feature Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    {{-- <div class="mb-2">
                        <label class="form-label">Category</label>
                        <input type="text" name="category" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Order</label>
                        <input type="number" name="order" class="form-control">
                    </div> --}}
                    <button class="btn btn-success w-100">Add Feature</button>
                </form>
                @endcan

                <hr>

                <h6 class="mt-3">Existing Features</h6>
                <div class="table-responsive flex-grow-1 overflow-auto" style="max-height: 300px; overflow-y: auto;">
                    <table class="table table-bordered table-sm mb-0">
                        <thead class="table-light" style="position: sticky; top: 0; z-index: 10;">
                            <tr>
                                <th>Name</th>
                                {{-- <th>Category</th>
                                <th>Order</th> --}}
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($features as $feature)
                                <tr>
                                    <td>{{ $feature->name }}</td>
                                    {{-- <td>{{ $feature->category }}</td>
                                    <td>{{ $feature->order }}</td> --}}
                                    <td>
                                        @can('Plan Edit')
                                        <button type="button" class="btn btn-sm btn-outline-primary editFeatureBtn" data-id="{{ $feature->id }}">Edit</button>
                                        @endcan
                                        @can('Plan Delete')
                                        <form action="{{ route('features.destroy', $feature->id) }}" method="POST" style="display:inline-block;">
                                            @csrf @method('DELETE')
                                            <button type="submit" onclick="return confirm('Delete this feature?')" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
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
</div>


<!-- Mapping -->
@can('Plan Edit')
<div class="card mt-4">
    <div class="card-body">
        <h5>Plan Feature Mapping</h5>
        <form action="{{ route('plans.updateMapping') }}" method="POST">
            @csrf
            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center">
                    <thead>
                        <tr>
                            <th>Feature</th>
                            @foreach($plans as $plan)
                                <th>{{ $plan->name }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($features as $feature)
                            <tr>
                                <td class="text-start">{{ $feature->name }}</td>
                                @foreach($plans as $plan)
                                    @php
                                        $pivot = $plan->planFeatures->firstWhere('feature_id', $feature->id);
                                    @endphp
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <select name="mapping[{{ $plan->id }}][{{ $feature->id }}][availability]" 
                                                    class="form-select form-select-sm w-auto">
                                                <option value="no" {{ $pivot?->availability == 'no' ? 'selected' : '' }}>No</option>
                                                <option value="partial" {{ $pivot?->availability == 'partial' ? 'selected' : '' }}>Partial</option>
                                                <option value="yes" {{ $pivot?->availability == 'yes' ? 'selected' : '' }}>Yes</option>
                                            </select>

                                            <textarea
                                                class="form-control form-control-sm w-50"
                                                name="mapping[{{ $plan->id }}][{{ $feature->id }}][details]"
                                                placeholder="Details">{{ $pivot?->details }}</textarea>

                                            <input type="number" 
                                                class="form-control form-control-sm w-25"
                                                name="mapping[{{ $plan->id }}][{{ $feature->id }}][limit]"
                                                value="{{ $pivot?->limit }}"
                                                placeholder="Limit">
                                        </div>
                                        <div>
                                            <select 
                                                name="feature_permissions[{{ $plan->id }}][{{ $feature->id }}][]" 
                                                class="form-select form-select-sm mt-1 select2-permissions" 
                                                multiple
                                            >

                                                {{-- @foreach($allPermissions as $permission)
                                                    <option value="{{ $permission->id }}"
                                                        {{ $feature->permissions->contains($permission->id) ? 'selected' : '' }}>
                                                        {{ $permission->name }}
                                                    </option>
                                                @endforeach --}}
                                                @php
                                                    $existingPermissionIds = \App\Models\PlanFeaturePermission::where('plan_id', $plan->id)
                                                        ->where('feature_id', $feature->id)
                                                        ->pluck('permission_id')
                                                        ->toArray();
                                                @endphp

                                                @foreach($allPermissions as $permission)
                                                    <option value="{{ $permission->id }}"
                                                        {{ in_array($permission->id, $existingPermissionIds) ? 'selected' : '' }}>
                                                        {{ $permission->name }}
                                                    </option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <button class="btn btn-primary mt-3">Update Mapping</button>
        </form>
    </div>
</div>
@endcan

<!-- Edit Plan Modal -->
<div class="modal fade" id="editPlanModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="editPlanForm" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Plan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-2">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="mb-2">
            <label>Price</label>
            <input type="number" name="price" step="0.01" class="form-control" required>
          </div>
          <div class="mb-2">
            <label>Coins</label>
            <input type="number" name="coins" class="form-control" required>
          </div>
          <div class="mb-2">
            <label>Task Coin Cost</label>
            <input type="number" name="task_coin_cost" class="form-control" required>
          </div>
          <div class="mb-2">
            <label>Description</label>
            <textarea name="description" class="form-control"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary">Update</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Edit Feature Modal -->
<div class="modal fade" id="editFeatureModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="editFeatureForm" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Feature</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-2">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="mb-2 d-none">
            <label>Category</label>
            <input type="text" name="category" class="form-control">
          </div>
          <div class="mb-2 d-none">
            <label>Order</label>
            <input type="number" name="order" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary">Update</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.select2-permissions').select2({
        placeholder: "Select permissions",
        allowClear: true,
        width: '100%',
    });
});
</script>

<script>
$(function() {
    // Edit Plan
    $('.editPlanBtn').click(function() {
        let id = $(this).data('id');

        // Build the route URL using Blade
        let editUrl = "{{ route('plans.edit', ':id') }}".replace(':id', id);
        let updateUrl = "{{ route('plans.update', ':id') }}".replace(':id', id);

        $.get(editUrl, function(data) {
            let modal = $('#editPlanModal');
            modal.find('form').attr('action', updateUrl);
            modal.find('[name=name]').val(data.name);
            modal.find('[name=price]').val(data.price);
            modal.find('[name=coins]').val(data.coins);
            modal.find('[name=task_coin_cost]').val(data.task_coin_cost);
            modal.find('[name=description]').val(data.description);
            modal.modal('show');
        });
    });

    // Edit Feature
    $('.editFeatureBtn').click(function() {
        let id = $(this).data('id');

        let editUrl = "{{ route('features.edit', ':id') }}".replace(':id', id);
        let updateUrl = "{{ route('features.update', ':id') }}".replace(':id', id);

        $.get(editUrl, function(data) {
            let modal = $('#editFeatureModal');
            modal.find('form').attr('action', updateUrl);
            modal.find('[name=name]').val(data.name);
            modal.find('[name=category]').val(data.category);
            modal.find('[name=order]').val(data.order);
            modal.modal('show');
        });
    });
});
</script>
@endsection

