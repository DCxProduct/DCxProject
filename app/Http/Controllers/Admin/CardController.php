<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Card;
use Illuminate\Http\Request;
use RuntimeException;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class CardController extends Controller
{
    public function index()
    {
        $cards = Card::latest()->paginate(10);

        return view('admins.cards.index', compact('cards'));
    }

    public function create()
    {
        return view('admins.cards.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'link_url' => ['nullable', 'url', 'max:255'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        try {
            $data['image_path'] = $this->handleUpload($request);
        } catch (FileException|RuntimeException $e) {
            return back()
                ->withInput()
                ->withErrors([
                    'image' => 'Image upload failed. Please check folder permission for public/uploads/cards.',
                ]);
        }

        Card::create($data);

        return redirect()->route('admin.cards.index')->with('success', 'Card created.');
    }

    public function edit(Card $card)
    {
        return view('admins.cards.edit', compact('card'));
    }

    public function show(Card $card)
    {
        return view('admins.cards.show', compact('card'));
    }

    public function update(Request $request, Card $card)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'link_url' => ['nullable', 'url', 'max:255'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        try {
            $newImagePath = $this->handleUpload($request);
        } catch (FileException|RuntimeException $e) {
            return back()
                ->withInput()
                ->withErrors([
                    'image' => 'Image upload failed. Please check folder permission for public/uploads/cards.',
                ]);
        }

        if ($newImagePath) {
            $this->deleteOldImage($card->image_path);
            $data['image_path'] = $newImagePath;
        }

        $card->update($data);

        return redirect()->route('admin.cards.index')->with('success', 'Card updated.');
    }

    public function destroy(Card $card)
    {
        $this->deleteOldImage($card->image_path);
        $card->delete();

        return redirect()->route('admin.cards.index')->with('success', 'Card deleted.');
    }

    private function handleUpload(Request $request): ?string
    {
        if (!$request->hasFile('image')) {
            return null;
        }

        $file = $request->file('image');
        $uploadDir = public_path('uploads/cards');

        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
                throw new RuntimeException('Unable to create upload directory.');
            }
        }

        if (!is_writable($uploadDir)) {
            throw new RuntimeException('Upload directory is not writable.');
        }

        $filename = uniqid('card_', true) . '.' . $file->getClientOriginalExtension();
        $file->move($uploadDir, $filename);

        return 'uploads/cards/' . $filename;
    }

    private function deleteOldImage(?string $imagePath): void
    {
        if (!$imagePath) {
            return;
        }

        $fullPath = public_path($imagePath);
        if (is_file($fullPath)) {
            unlink($fullPath);
        }
    }
}
