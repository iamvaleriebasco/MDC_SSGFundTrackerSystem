@extends('layouts.app')
@section('title', 'Funds – SSG Fund Tracker')

@section('breadcrumb')
    <li class="breadcrumb-item active">Funds</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-0" style="color:#1a3a6b;">Funds</h4>
        <small class="text-muted">Manage SSG fund allocations</small>
    </div>
    <a href="{{ route('funds.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i> New Fund
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>School Year</th>
                        <th>Semester</th>
                        <th>Allocated</th>
                        <th>Balance</th>
                        <th>Txns</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($funds as $fund)
                    <tr>
                        <td class="text-muted" style="font-size:.8rem;">{{ $loop->iteration }}</td>
                        <td>
                            <a href="{{ route('funds.show', $fund) }}"
                               class="fw-600 text-decoration-none text-primary"
                               style="font-size:.9rem;">
                                {{ $fund->name }}
                            </a>
                            @if($fund->description)
                                <div class="text-muted" style="font-size:.75rem;">
                                    {{ Str::limit($fund->description, 50) }}
                                </div>
                            @endif
                        </td>
                        <td style="font-size:.85rem;">{{ $fund->school_year }}</td>
                        <td style="font-size:.85rem;">{{ $fund->semester }}</td>
                        <td style="font-size:.87rem;">₱{{ number_format($fund->allocated_amount, 2) }}</td>
                        <td class="fw-700" style="font-size:.9rem; color:#1a3a6b;">
                            ₱{{ number_format($fund->current_balance, 2) }}
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                {{ $fund->transactions_count }}
                            </span>
                        </td>
                        <td>
                            @php
                                $colors = ['active' => 'success', 'closed' => 'secondary', 'archived' => 'warning'];
                            @endphp
                            <span class="badge bg-{{ $colors[$fund->status] ?? 'secondary' }}-subtle
                                              text-{{ $colors[$fund->status] ?? 'secondary' }}"
                                  style="font-size:.72rem;">
                                {{ ucfirst($fund->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('funds.show', $fund) }}"
                                   class="btn btn-sm btn-outline-primary py-0 px-2" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('funds.edit', $fund) }}"
                                   class="btn btn-sm btn-outline-secondary py-0 px-2" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('funds.destroy', $fund) }}"
                                      onsubmit="return confirm('Delete this fund?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger py-0 px-2" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-5">
                            <i class="bi bi-wallet2 fs-3 d-block mb-2 opacity-30"></i>
                            No funds found.
                            <a href="{{ route('funds.create') }}">Create one now</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($funds->hasPages())
        <div class="card-footer bg-transparent">
            {{ $funds->links() }}
        </div>
    @endif
</div>
@endsection
