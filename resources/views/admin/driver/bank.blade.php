@extends('layouts.app')

@section('title', 'Driver Bank Account')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Driver Bank Account</h4>
        <a class="btn btn-primary" href="{{ route('driver.index') }}">Back to Drivers</a>
    </div>
    <div class="alert alert-info">
        Step 3 of 3: Add bank details for <strong>{{ $driver->user->name }}</strong>.
    </div>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif
    @php
        $submitted = $bank && in_array($bank->status, ['submitting', 'pending', 'verified']);
        $retryOnly = $bank && $bank->status === 'unknown';
    @endphp
    @if ($bank)
        <div class="card mb-3">
            <div class="card-body">
                <p class="mb-1"><strong>Account:</strong> {{ $bank->maskedAccountNumber() }}</p>
                <p class="mb-0"><strong>Verification:</strong> {{ ucfirst($bank->status) }}</p>
                @if ($bank->status === 'pending')
                    <p class="text-muted mt-2 mb-0">Your request has been accepted. The account is awaiting final verification.</p>
                @endif
            </div>
        </div>
    @endif
    @if ($bank && in_array($bank->status, ['pending', 'unknown', 'submitting']))
        <form action="{{ route('driver.bank.status', $driver) }}" method="post" class="mb-3">
            @csrf
            <button type="submit" class="btn btn-primary">Check Verification Status</button>
        </form>
    @endif
    @if (! $submitted)
        <form action="{{ route('driver.bank.store', $driver) }}" method="post" id="bankAccountForm">
            @csrf
            <div class="card">
                <div class="card-header">Bank Details</div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach (['bank_name' => 'Bank Name', 'branch_name' => 'Branch Name', 'account_number' => 'A/c Number', 'account_holder_name' => 'A/c Holder Name', 'ifsc' => 'IFSC Code'] as $field => $label)
                            <div class="col-md-6">
                                <label class="form-label" for="{{ $field }}">{{ $label }} <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="{{ $field }}" name="{{ $field }}"
                                    value="{{ old($field, $bank?->$field) }}" required
                                    maxlength="{{ $field === 'ifsc' ? 11 : ($field === 'account_number' ? 34 : 255) }}"
                                    @if ($field === 'account_number') inputmode="numeric" pattern="[0-9]{6,34}" autocomplete="off" @endif
                                    @if ($field === 'ifsc') style="text-transform: uppercase" pattern="[A-Za-z]{4}0[A-Za-z0-9]{6}" @endif
                                    @readonly($retryOnly)>
                            </div>
                        @endforeach
                    </div>
                    <p class="text-muted mt-3">The IFSC code is checked before submitting the account for verification.</p>
                    <button class="btn btn-primary" type="submit" id="bankSubmit">{{ $retryOnly ? 'Retry Verification' : 'Save & Verify Bank Account' }}</button>
                    <a class="btn btn-outline-secondary" href="{{ route('driver.kyc', $driver->id) }}">Back to KYC</a>
                </div>
            </div>
        </form>
    @endif
@endsection

@section('scripts')
<script>
    document.getElementById('bankAccountForm')?.addEventListener('submit', function () {
        const button = document.getElementById('bankSubmit');
        button.disabled = true;
        button.textContent = 'Submitting verification...';
    });
</script>
@endsection
