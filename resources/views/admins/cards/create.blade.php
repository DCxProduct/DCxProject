@extends('admins.master')

@section('content')
    <div class="container my-5" style="max-width: 720px;">
        <h4 class="fw-bold mb-4">Create Application</h4>

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
                        <textarea name="description" class="form-control" rows="3" maxlength="130">{{ old('description') }}</textarea>
                        <div class="form-text">Maximum 130 characters.</div>
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
                        <label class="form-label">Select Application Destination</label>
                        <select name="destination_type" id="destination_type" class="form-select" required>
                            <option value="url" {{ old('destination_type', 'url') === 'url' ? 'selected' : '' }}>URL</option>
                            <option value="folder" {{ old('destination_type') === 'folder' ? 'selected' : '' }}>Folder</option>
                        </select>
                        @error('destination_type')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3" id="link_url_group">
                        <label class="form-label">Link URL</label>
                        <input type="url" name="link_url" id="link_url" class="form-control" value="{{ old('link_url') }}">
                        @error('link_url')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3" id="parent_folder_group">
                        <label class="form-label">Place In Folder (Optional)</label>
                        <select name="parent_id" id="parent_id" class="form-select">
                            <option value="">Main Dashboard</option>
                            @foreach ($folderOptions as $folder)
                                <option value="{{ $folder->id }}" {{ (string) old('parent_id') === (string) $folder->id ? 'selected' : '' }}>
                                    {{ $folder->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('parent_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="require_login" id="require_login" value="1" {{ old('require_login') ? 'checked' : '' }}>
                        <label class="form-check-label" for="require_login">
                            Require login before opening this Application
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const destinationSelect = document.getElementById('destination_type');
            const linkUrlGroup = document.getElementById('link_url_group');
            const linkInput = document.getElementById('link_url');
            const parentFolderGroup = document.getElementById('parent_folder_group');
            const parentIdSelect = document.getElementById('parent_id');

            if (!destinationSelect || !linkUrlGroup || !linkInput || !parentFolderGroup || !parentIdSelect) {
                return;
            }

            const toggleDestinationFields = () => {
                const isUrl = destinationSelect.value === 'url';
                linkUrlGroup.style.display = isUrl ? '' : 'none';
                parentFolderGroup.style.display = isUrl ? '' : 'none';
                parentIdSelect.disabled = !isUrl;

                if (!isUrl) {
                    linkInput.value = '';
                    parentIdSelect.value = '';
                }
            };

            destinationSelect.addEventListener('change', toggleDestinationFields);
            toggleDestinationFields();
        });
    </script>
@endsection
