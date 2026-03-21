{{-- Shared form fields for Fund create / edit --}}
<div class="row g-3">
    <div class="col-12">
        <label class="form-label fw-500">Fund Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $fund->name ?? '') }}" placeholder="e.g. General Fund">
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label class="form-label fw-500">Description</label>
        <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                  rows="3" placeholder="Brief description of the fund's purpose...">{{ old('description', $fund->description ?? '') }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-500">Allocated Amount (₱) <span class="text-danger">*</span></label>
        <input type="number" name="allocated_amount" step="0.01" min="0"
               class="form-control @error('allocated_amount') is-invalid @enderror"
               value="{{ old('allocated_amount', $fund->allocated_amount ?? '') }}"
               placeholder="0.00">
        @error('allocated_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-500">Status <span class="text-danger">*</span></label>
        <select name="status" class="form-select @error('status') is-invalid @enderror">
            @foreach(['active', 'closed', 'archived'] as $s)
                <option value="{{ $s }}"
                    {{ old('status', $fund->status ?? 'active') === $s ? 'selected' : '' }}>
                    {{ ucfirst($s) }}
                </option>
            @endforeach
        </select>
        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-500">School Year <span class="text-danger">*</span></label>
        <input type="text" name="school_year"
               class="form-control @error('school_year') is-invalid @enderror"
               value="{{ old('school_year', $fund->school_year ?? '') }}"
               placeholder="e.g. 2024-2025">
        @error('school_year')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-500">Semester <span class="text-danger">*</span></label>
        <select name="semester" class="form-select @error('semester') is-invalid @enderror">
            @php $selSem = old('semester', $fund->semester ?? ''); @endphp
            <option value="">-- Select --</option>
            @foreach(['1st Semester', '2nd Semester', 'Summer'] as $sem)
                <option value="{{ $sem }}" {{ $selSem === $sem ? 'selected' : '' }}>
                    {{ $sem }}
                </option>
            @endforeach
        </select>
        @error('semester')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
