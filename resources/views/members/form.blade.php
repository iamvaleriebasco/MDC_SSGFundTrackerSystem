{{-- resources/views/members/_form.blade.php --}}
{{-- Shared fields for create / edit --}}
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-500">Student ID <span class="text-danger">*</span></label>
        <input type="text" name="student_id"
               class="form-control @error('student_id') is-invalid @enderror"
               value="{{ old('student_id', $member->student_id ?? '') }}"
               placeholder="e.g. 2024-00001">
        @error('student_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-500">Full Name <span class="text-danger">*</span></label>
        <input type="text" name="name"
               class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $member->name ?? '') }}"
               placeholder="Juan dela Cruz">
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-500">Email <span class="text-danger">*</span></label>
        <input type="email" name="email"
               class="form-control @error('email') is-invalid @enderror"
               value="{{ old('email', $member->email ?? '') }}"
               placeholder="student@edu.ph">
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-500">Course <span class="text-danger">*</span></label>
        <input type="text" name="course"
               class="form-control @error('course') is-invalid @enderror"
               value="{{ old('course', $member->course ?? '') }}"
               placeholder="BS Computer Science">
        @error('course')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-500">Year Level <span class="text-danger">*</span></label>
        <select name="year_level" class="form-select @error('year_level') is-invalid @enderror">
            <option value="">-- Select --</option>
            @foreach(['1st Year','2nd Year','3rd Year','4th Year','5th Year'] as $yr)
                <option value="{{ $yr }}"
                    {{ old('year_level', $member->year_level ?? '') === $yr ? 'selected' : '' }}>
                    {{ $yr }}
                </option>
            @endforeach
        </select>
        @error('year_level')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-500">Position</label>
        <input type="text" name="position"
               class="form-control @error('position') is-invalid @enderror"
               value="{{ old('position', $member->position ?? '') }}"
               placeholder="SSG Position">
        @error('position')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_active"
                   id="is_active" value="1"
                   {{ old('is_active', $member->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Active Member</label>
        </div>
    </div>
</div>
