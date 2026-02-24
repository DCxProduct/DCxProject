<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();

        return view('public.profile', [
            'user' => $user,
            'avatarUrl' => $user->avatarRelativePath() ? asset($user->avatarRelativePath()) : null,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('users', 'name')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];

        if (!empty($data['password'])) {
            $user->password = $data['password'];
        }

        if ($request->hasFile('avatar')) {
            $this->storeAvatar($user->id, $request->file('avatar'));
        }

        $user->save();

        $request->session()->put('user_last_activity_at', time());

        return back()->with('success', 'Profile updated successfully.');
    }

    private function storeAvatar(int $userId, UploadedFile $file): void
    {
        $avatarDirectory = public_path('uploads/avatars');
        File::ensureDirectoryExists($avatarDirectory);

        foreach (glob($avatarDirectory.DIRECTORY_SEPARATOR.'user_'.$userId.'.*') ?: [] as $existing) {
            if (is_file($existing)) {
                @unlink($existing);
            }
        }

        $extension = strtolower($file->getClientOriginalExtension());
        $filename = 'user_'.$userId.'.'.$extension;
        $file->move($avatarDirectory, $filename);
    }
}
