@extends('admins.master')

@section('content')
    <div class="container my-5" style="max-width: 720px;">
        <h4 class="fw-bold mb-4">Create Card</h4>

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.cards.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Order Number</label>
                        <input type="number" name="shape_number" class="form-control" min="0" step="1" value="{{ old('shape_number') }}">
                        @error('shape_number')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Link URL</label>
                        <input type="url" name="link_url" class="form-control" value="{{ old('link_url') }}">
                        @error('link_url')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="require_login" id="require_login" value="1" {{ old('require_login') ? 'checked' : '' }}>
                        <label class="form-check-label" for="require_login">
                            Require login before opening this card
                        </label>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Upload Image</label>
                        <input type="file" name="image" class="form-control">
                        @error('image')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">Create</button>
                        <a href="{{ route('admin.cards.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
