{{-- Shared transaction form fields --}}
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-500">Fund <span class="text-danger">*</span></label>
        <select name="fund_id" class="form-select @error('fund_id') is-invalid @enderror">
            <option value="">-- Select Fund --</option>
            @foreach($funds as $fund)
                <option value="{{ $fund->id }}"
                    {{ old('fund_id', $transaction->fund_id ?? '') == $fund->id ? 'selected' : '' }}>
                    {{ $fund->name }}
                </option>
            @endforeach
        </select>
        @error('fund_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-500">Member</label>
        <select name="member_id" class="form-select @error('member_id') is-invalid @enderror">
            <option value="">-- No Member --</option>
            @foreach($members as $member)
                <option value="{{ $member->id }}"
                    {{ old('member_id', $transaction->member_id ?? '') == $member->id ? 'selected' : '' }}>
                    {{ $member->student_id }} – {{ $member->name }}
                </option>
            @endforeach
        </select>
        @error('member_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-500">Type <span class="text-danger">*</span></label>
        <select name="type" id="txType" class="form-select @error('type') is-invalid @enderror">
            <option value="">-- Select Type --</option>
            <option value="income"  {{ old('type', $transaction->type ?? '') === 'income'  ? 'selected' : '' }}>Income</option>
            <option value="expense" {{ old('type', $transaction->type ?? '') === 'expense' ? 'selected' : '' }}>Expense</option>
        </select>
        @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-500">Category <span class="text-danger">*</span></label>
        <select name="category" id="txCategory" class="form-select @error('category') is-invalid @enderror">
            <option value="">-- Select Category --</option>
            @php $selCat = old('category', $transaction->category ?? ''); @endphp
            <optgroup label="Income" id="incomeGroup">
                @foreach(\App\Http\Controllers\TransactionController::INCOME_CATEGORIES as $c)
                    <option value="{{ $c }}" {{ $selCat === $c ? 'selected' : '' }}>{{ $c }}</option>
                @endforeach
            </optgroup>
            <optgroup label="Expense" id="expenseGroup">
                @foreach(\App\Http\Controllers\TransactionController::EXPENSE_CATEGORIES as $c)
                    <option value="{{ $c }}" {{ $selCat === $c ? 'selected' : '' }}>{{ $c }}</option>
                @endforeach
            </optgroup>
        </select>
        @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-500">Amount (₱) <span class="text-danger">*</span></label>
        <input type="number" name="amount" step="0.01" min="0.01"
               class="form-control @error('amount') is-invalid @enderror"
               value="{{ old('amount', $transaction->amount ?? '') }}"
               placeholder="0.00">
        @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label class="form-label fw-500">Description <span class="text-danger">*</span></label>
        <textarea name="description" rows="3"
                  class="form-control @error('description') is-invalid @enderror"
                  placeholder="Describe the transaction...">{{ old('description', $transaction->description ?? '') }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-500">Transaction Date <span class="text-danger">*</span></label>
        <input type="date" name="transaction_date"
               class="form-control @error('transaction_date') is-invalid @enderror"
               value="{{ old('transaction_date', isset($transaction->transaction_date) ? $transaction->transaction_date->format('Y-m-d') : date('Y-m-d')) }}">
        @error('transaction_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-500">Receipt Number</label>
        <input type="text" name="receipt_number"
               class="form-control @error('receipt_number') is-invalid @enderror"
               value="{{ old('receipt_number', $transaction->receipt_number ?? '') }}"
               placeholder="Optional">
        @error('receipt_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Status field — Treasurer sees dropdown, others see read-only badge ── --}}
    @isset($transaction)
        @if(Auth::user()->role === 'treasurer')
            <div class="col-md-6">
                <label class="form-label fw-500">Status</label>
                <select name="status" class="form-select @error('status') is-invalid @enderror">
                    @foreach(['pending','approved','rejected'] as $s)
                        <option value="{{ $s }}"
                            {{ old('status', $transaction->status) === $s ? 'selected' : '' }}>
                            {{ ucfirst($s) }}
                        </option>
                    @endforeach
                </select>
                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        @else
            {{-- Pass current status as hidden so form submission still works --}}
            <input type="hidden" name="status" value="{{ $transaction->status }}">
            <div class="col-md-6">
                <label class="form-label fw-500">Status</label>
                <div class="form-control bg-light d-flex align-items-center gap-2"
                     style="cursor:not-allowed;">
                    <span class="badge badge-{{ $transaction->status }}" style="font-size:.78rem;">
                        {{ ucfirst($transaction->status) }}
                    </span>
                    <small class="text-muted">Only the Treasurer can change this.</small>
                </div>
            </div>
        @endif
    @endisset
    {{-- ──────────────────────────────────────────────────────────────────── --}}

</div>

@push('scripts')
<script>
    const typeEl     = document.getElementById('txType');
    const incomeGrp  = document.getElementById('incomeGroup');
    const expenseGrp = document.getElementById('expenseGroup');

    function filterCategories() {
        const val = typeEl.value;
        incomeGrp.style.display  = (val === 'expense') ? 'none' : '';
        expenseGrp.style.display = (val === 'income')  ? 'none' : '';
    }

    typeEl.addEventListener('change', filterCategories);
    filterCategories();
</script>
@endpush
