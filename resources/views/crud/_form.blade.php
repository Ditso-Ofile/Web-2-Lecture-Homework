{{-- TASK 9 - Form fields shared by create.blade.php and edit.blade.php --}}
{{-- old('field', $town->field): after a validation error show what was typed, otherwise the stored value --}}
<div class="mb-3">
    <label for="tname" class="form-label">Town name *</label>
    <input type="text" id="tname" name="tname" value="{{ old('tname', $town->tname ?? '') }}"
           class="form-control @error('tname') is-invalid @enderror">
    @error('tname') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="countyid" class="form-label">County *</label>
    <select id="countyid" name="countyid" class="form-select @error('countyid') is-invalid @enderror">
        <option value="">Choose a county…</option>
        @foreach ($counties as $county)
            <option value="{{ $county->id }}" @selected(old('countyid', $town->countyid ?? '') == $county->id)>{{ $county->cname }}</option>
        @endforeach
    </select>
    @error('countyid') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="form-check mb-2">
    <input class="form-check-input" type="checkbox" id="countyseat" name="countyseat" value="1"
           @checked(old('countyseat', $town->countyseat ?? false))>
    <label class="form-check-label" for="countyseat">County seat</label>
</div>

<div class="form-check mb-4">
    <input class="form-check-input" type="checkbox" id="countylevel" name="countylevel" value="1"
           @checked(old('countylevel', $town->countylevel ?? false))>
    <label class="form-check-label" for="countylevel">County-level town</label>
</div>
