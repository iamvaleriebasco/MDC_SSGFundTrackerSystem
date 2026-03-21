@extends('layouts.app')
@section('title', $member->name . ' – SSG Fund Tracker')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('members.index') }}">Members</a></li>
    <li class="breadcrumb-item active">{{ $member->name }}</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-0" style="color:#1a3a6b;">{{ $member->name }}</h4>
        <small class="text-muted">{{ $member->student_id }} &bull; {{ $member->course }}</small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('members.edit', $member) }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-pencil me-1"></i> Edit
        </a>
        <a href="{{ route('members.index') }}" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header"><i class="bi bi-person me-2 text-primary"></i>Member Info</div>
            <div class="card-body">
                @php
                    $rows = [
                        'Email'      => $member->email,
                        'Course'     => $member->course,
                        'Year'       => $member->year_level,
                        'Section'    => $member->section,
                        'Position'   => $member->position ?? '—',
                        'Status'     => $member->is_active ? 'Active' : 'Inactive',
                        'Registered' => $member->created_at->format('M d, Y'),
                    ];
                @endphp
                @foreach($rows as $label => $value)
                    <div class="d-flex justify-content-between py-1 border-bottom" style="font-size:.85rem;">
                        <span class="text-muted">{{ $label }}</span>
                        <span class="fw-500">{{ $value }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-arrow-left-right me-2 text-primary"></i>Transaction History</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="font-size:.84rem;">
                        <thead class="table-light">
                            <tr>
                                <th>Reference</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $tx)
                            <tr>
                                <td>
                                    <a href="{{ route('transactions.show', $tx) }}"
                                       class="text-decoration-none text-primary">
                                        {{ $tx->reference_number }}
                                    </a>
                                </td>
                                <td>
                                    <span class="badge {{ $tx->type === 'income' ? 'badge-income' : 'badge-expense' }}"
                                          style="font-size:.7rem;">
                                        {{ ucfirst($tx->type) }}
                                    </span>
                                </td>
                                <td class="fw-600">₱{{ number_format($tx->amount, 2) }}</td>
                                <td>
                                    <span class="badge badge-{{ $tx->status }}" style="font-size:.7rem;">
                                        {{ ucfirst($tx->status) }}
                                    </span>
                                </td>
                                <td class="text-muted">{{ $tx->transaction_date->format('M d, Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No transactions.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($transactions->hasPages())
                <div class="card-footer bg-transparent">{{ $transactions->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
