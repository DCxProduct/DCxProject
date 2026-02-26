@extends('admins.master')

@section('content')
    <div class="container my-5" style="max-width: 720px;">
        <h4 class="fw-bold mb-4">Edit Card</h4>

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.cards.update', $card) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $card->name) }}" required>
                        @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3" maxlength="130">{{ old('description', $card->description) }}</textarea>
                        <div class="form-text">Maximum 130 characters.</div>
                        @error('description')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Order Number</label>
                        <input type="number" name="shape_number" class="form-control" min="0" step="1" value="{{ old('shape_number', $card->shape_number) }}">
                        @error('shape_number')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Link URL</label>
                        <input type="url" name="link_url" class="form-control" value="{{ old('link_url', $card->link_url) }}">
                        @error('link_url')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="require_login" id="require_login" value="1" {{ old('require_login', $card->require_login) ? 'checked' : '' }}>
                        <label class="form-check-label" for="require_login">
                            Require login before opening this card
                        </label>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Upload New Image</label>
                        <input type="file" name="image" class="form-control">
                        @error('image')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('admin.cards.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
