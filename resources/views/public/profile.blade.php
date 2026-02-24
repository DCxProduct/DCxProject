@extends('layouts.master')

@section('title', 'My Profile')

@section('content')
    @php
        $avatarPreview = $avatarUrl ?: 'https://ui-avatars.com/api/?name='.urlencode((string) $user->name).'&background=0d9488&color=ffffff&size=240';
        $userHandle = '@'.preg_replace('/[^a-z0-9._]/i', '', strtolower((string) \Illuminate\Support\Str::before($user->email, '@')));
    @endphp

    <style>
        .profile-side-card {
            border: 0;
            border-radius: 18px;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
        }

        .profile-avatar-wrap {
            width: 108px;
            height: 108px;
            margin: 0 auto 10px;
            border-radius: 50%;
            overflow: visible;
            position: relative;
        }

        .profile-avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            border-radius: 50%;
        }

        .profile-avatar-edit {
            position: absolute;
            right: -2px;
            bottom: -2px;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            border: 0;
            background: #ffffff;
            box-shadow: 0 4px 10px rgba(15, 23, 42, 0.2);
            color: #0f172a;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            line-height: 1;
            z-index: 2;
            cursor: pointer;
        }
    </style>

    <div class="container py-5">
        <div class="row g-4 align-items-start">
            <div class="col-12 col-lg-3">
                <div class="card profile-side-card">
                    <div class="card-body text-center p-4">
                        <div class="profile-avatar-wrap">
                            <img id="profile-avatar-preview" src="{{ $avatarPreview }}" alt="{{ $user->name }}" class="profile-avatar-img">
                            <label for="avatar" class="profile-avatar-edit" title="Change photo">&#9998;</label>
                        </div>
                        <h5 id="profile-preview-name" class="mb-1 text-dark fw-bold">{{ $user->name }}</h5>
                        <div id="profile-preview-handle" class="text-muted">{{ $userHandle }}</div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-9">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4 p-md-5">
                        <h3 class="mb-1 fw-bold text-dark">My Profile</h3>
                        <p class="text-muted mb-4">Update your account details for the public dashboard.</p>

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger mb-4">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                            @csrf
                            @method('PUT')

                            <input id="avatar" name="avatar" type="file" class="d-none" accept=".jpg,.jpeg,.png,.webp">

                            <div class="col-12 col-md-6">
                                <label for="name" class="form-label fw-semibold text-dark">Username</label>
                                <input
                                    id="name"
                                    name="name"
                                    type="text"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}"
                                    required
                                >
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="email" class="form-label fw-semibold text-dark">Email</label>
                                <input
                                    id="email"
                                    name="email"
                                    type="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $user->email) }}"
                                    required
                                >
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="password" class="form-label fw-semibold text-dark">New Password (Optional)</label>
                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Leave blank to keep current password"
                                >
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="password_confirmation" class="form-label fw-semibold text-dark">Confirm Password</label>
                                <input
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    type="password"
                                    class="form-control"
                                    placeholder="Repeat new password"
                                >
                            </div>

                            <div class="col-12 d-flex justify-content-end gap-2 pt-2">
                                <a href="{{ url('/') }}" class="btn btn-light border">Back</a>
                                <button type="submit" class="btn btn-warning px-4">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const avatarInput = document.getElementById('avatar');
        const avatarPreviewElement = document.getElementById('profile-avatar-preview');
        const nameInput = document.getElementById('name');
        const emailInput = document.getElementById('email');
        const profilePreviewName = document.getElementById('profile-preview-name');
        const profilePreviewHandle = document.getElementById('profile-preview-handle');
        const hasStoredAvatar = @json((bool) $avatarUrl);
        let hasUploadedAvatar = false;

        const toHandle = (emailValue, nameValue) => {
            const raw = (emailValue || '').trim();
            const localPart = raw.includes('@') ? raw.split('@')[0] : raw;
            const fallback = (nameValue || '').trim();
            const source = localPart !== '' ? localPart : fallback;
            const clean = source.toLowerCase().replace(/[^a-z0-9._]/g, '');
            return `@${clean || 'user'}`;
        };

        const uiAvatarUrl = (nameValue) => {
            const safeName = (nameValue || '').trim() || 'User';
            return `https://ui-avatars.com/api/?name=${encodeURIComponent(safeName)}&background=0d9488&color=ffffff&size=240`;
        };

        const updateProfilePreview = () => {
            const currentName = (nameInput?.value || '').trim() || 'User';
            const currentEmail = (emailInput?.value || '').trim();

            if (profilePreviewName) {
                profilePreviewName.textContent = currentName;
            }

            if (profilePreviewHandle) {
                profilePreviewHandle.textContent = toHandle(currentEmail, currentName);
            }

            if (avatarPreviewElement && !hasStoredAvatar && !hasUploadedAvatar) {
                avatarPreviewElement.src = uiAvatarUrl(currentName);
            }
        };

        if (avatarInput && avatarPreviewElement) {
            avatarInput.addEventListener('change', (event) => {
                const [file] = event.target.files || [];
                if (!file) {
                    return;
                }

                hasUploadedAvatar = true;
                avatarPreviewElement.src = URL.createObjectURL(file);
            });
        }

        if (nameInput) {
            nameInput.addEventListener('input', updateProfilePreview);
        }

        if (emailInput) {
            emailInput.addEventListener('input', updateProfilePreview);
        }

        updateProfilePreview();
    </script>
@endsection
