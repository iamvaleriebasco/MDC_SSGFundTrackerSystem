@extends('layouts.app')
@section('title', 'Edit Transaction – SSG Fund Tracker')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('transactions.index') }}">Transactions</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h4 class="fw-bold mb-0" style="color:#1a3a6b;">Edit Transaction</h4>
            <a href="{{ route('transactions.show', $transaction) }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>

        <div class="card">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('transactions.update', $transaction) }}">
                    @csrf @method('PUT')
                    @include('transactions.form')
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Update Transaction
                        </button>
                        <a href="{{ route('transactions.show', $transaction) }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
