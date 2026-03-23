@extends('layouts.app')
@section('title', 'Members – SSG Fund Tracker')

@section('breadcrumb')
    <li class="breadcrumb-item active">Members</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-0" style="color:#1a3a6b;">Members</h4>
        <small class="text-muted">SSG member registry</small>
    </div>
    <a href="{{ route('members.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Add Member
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Course</th>
                        <th>Position</th>
                        <th>Transactions</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $member)
                    <tr>
                        <td style="font-size:.83rem; color:#6b7280;">{{ $member->student_id }}</td>
                        <td>
                            <a href="{{ route('members.show', $member) }}"
                               class="fw-600 text-decoration-none text-primary"
                               style="font-size:.9rem;">
                                {{ $member->name }}
                            </a>
                            <div class="text-muted" style="font-size:.75rem;">{{ $member->email }}</div>
                        </td>
                        <td style="font-size:.85rem;">{{ $member->course }}</td>
                        <td style="font-size:.83rem;">{{ $member->position ?? '—' }}</td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                {{ $member->transactions_count }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $member->is_active ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}"
                                  style="font-size:.72rem;">
                                {{ $member->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('members.show', $member) }}"
                                   class="btn btn-sm btn-outline-primary py-0 px-2">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('members.edit', $member) }}"
                                   class="btn btn-sm btn-outline-secondary py-0 px-2">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('members.destroy', $member) }}"
                                      onsubmit="return confirm('Delete this member?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger py-0 px-2">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                            <i class="bi bi-people fs-3 d-block mb-2 opacity-30"></i>
                            No members found. <a href="{{ route('members.create') }}">Add one now</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($members->hasPages())
        <div class="card-footer bg-transparent">{{ $members->links() }}</div>
    @endif
</div>
@endsection
