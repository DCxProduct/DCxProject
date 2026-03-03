<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use RuntimeException;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class CardController extends Controller
{
    public function index()
    {
        $cards = Card::query()
            ->orderByRaw('shape_number IS NULL')
            ->orderBy('shape_number')
            ->latest('id')
            ->paginate(16);

        return view('admins.cards.index', compact('cards'));
    }

    public function create()
    {
        $folderOptions = $this->getFolderOptions();
        $nextShapeNumbersByParent = $this->getNextShapeNumbersByParent(
            $folderOptions->pluck('id')->map(fn ($id) => (int) $id)->all()
        );

        return view('admins.cards.create', compact('folderOptions', 'nextShapeNumbersByParent'));
    }

    public function store(Request $request)
    {
        $data = $this->validateCard($request);
        $requestedShapeNumber = $data['shape_number'] ?? null;
        $data['shape_number'] = null;
        $data['require_login'] = $request->boolean('require_login');
        $data['parent_id'] = $request->filled('parent_id') ? (int) $request->input('parent_id') : null;

        if (($data['destination_type'] ?? 'url') === 'folder') {
            $data['link_url'] = null;
        }

        try {
            $data['image_path'] = $this->handleUpload($request);
        } catch (FileException|RuntimeException $e) {
            return back()
                ->withInput()
                ->withErrors([
                    'image' => 'Image upload failed. Please check folder permission for public/uploads/cards.',
                ]);
        }

        $card = Card::create($data);
        $this->resequenceScope(
            $card->parent_id ? (int) $card->parent_id : null,
            (int) $card->id,
            $requestedShapeNumber !== null ? (int) $requestedShapeNumber : null
        );

        return redirect()->route('admin.cards.index')->with('success', 'Card created.');
    }

    public function edit(Card $card)
    {
        $folderOptions = $this->getFolderOptions($card->id);

        return view('admins.cards.edit', compact('card', 'folderOptions'));
    }

    public function show(Card $card)
    {
        return view('admins.cards.show', compact('card'));
    }

    public function update(Request $request, Card $card)
    {
        $oldParentId = $card->parent_id ? (int) $card->parent_id : null;
        $data = $this->validateCard($request, $card);
        $requestedShapeNumber = $data['shape_number'] ?? null;
        $data['shape_number'] = null;
        $data['require_login'] = $request->boolean('require_login');
        $data['parent_id'] = $request->filled('parent_id') ? (int) $request->input('parent_id') : null;

        if (($data['destination_type'] ?? 'url') === 'folder') {
            $data['link_url'] = null;
        }

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

        $newParentId = $card->parent_id ? (int) $card->parent_id : null;
        if ($oldParentId !== $newParentId) {
            $this->resequenceScope($oldParentId);
        }

        $this->resequenceScope(
            $newParentId,
            (int) $card->id,
            $requestedShapeNumber !== null ? (int) $requestedShapeNumber : null
        );

        return redirect()->route('admin.cards.index')->with('success', 'Card updated.');
    }

    public function destroy(Card $card)
    {
        $cardIdsToDelete = [$card->id];
        $affectedParentIds = [];

        if (($card->destination_type ?? 'url') === 'folder') {
            // MyISAM does not enforce FK cascades; delete descendants manually.
            $cardIdsToDelete = $this->collectDescendantCardIds($card->id);
        } else {
            // Guard against orphan children if parent card is removed.
            Card::query()
                ->where('parent_id', $card->id)
                ->update([
                    'parent_id' => null,
                    'shape_number' => null,
                ]);

            $affectedParentIds[] = null;
        }

        $cardsToDelete = Card::query()
            ->whereIn('id', $cardIdsToDelete)
            ->get(['id', 'image_path', 'parent_id']);

        foreach ($cardsToDelete as $cardToDelete) {
            $this->deleteOldImage($cardToDelete->image_path);
            $affectedParentIds[] = $cardToDelete->parent_id ? (int) $cardToDelete->parent_id : null;
        }

        Card::query()->whereIn('id', $cardIdsToDelete)->delete();

        $uniqueAffectedParentIds = [];
        foreach ($affectedParentIds as $parentId) {
            $key = $parentId === null ? 'root' : (string) $parentId;
            $uniqueAffectedParentIds[$key] = $parentId;
        }

        foreach ($uniqueAffectedParentIds as $parentId) {
            if ($parentId !== null && !Card::query()->whereKey($parentId)->exists()) {
                continue;
            }

            $this->resequenceScope($parentId);
        }

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

    private function validateCard(Request $request, ?Card $card = null): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'shape_number' => [
                'nullable',
                'integer',
                'min:1',
            ],
            'description' => ['nullable', 'string', 'max:100'],
            'destination_type' => ['required', Rule::in(['url', 'folder'])],
            'parent_id' => ['nullable', 'integer', 'exists:cards,id'],
            'link_url' => $request->input('destination_type') === 'url'
                ? ['required', 'url', 'max:255']
                : ['nullable', 'url', 'max:255'],
            'require_login' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'max:2048'],
        ];

        $validator = Validator::make($request->all(), $rules, [
            'link_url.required' => 'The Link URL field is required when destination is URL.',
        ]);

        $validator->after(function ($validator) use ($request, $card) {
            if (!$request->filled('parent_id')) {
                return;
            }

            $parentId = (int) $request->input('parent_id');
            $parentCard = Card::query()->find($parentId);

            if (!$parentCard || ($parentCard->destination_type ?? 'url') !== 'folder') {
                $validator->errors()->add('parent_id', 'Selected parent must be a folder.');
                return;
            }

            if ($card && $parentId === (int) $card->id) {
                $validator->errors()->add('parent_id', 'A card cannot be its own parent.');
                return;
            }

            if ($card && $this->isCardInDescendantTree($card->id, $parentId)) {
                $validator->errors()->add('parent_id', 'Invalid parent folder selection.');
            }
        });

        return $validator->validate();
    }

    private function getFolderOptions(?int $excludeCardId = null)
    {
        return Card::query()
            ->where('destination_type', 'folder')
            ->where(function ($query) {
                $query->whereNull('parent_id')
                    ->orWhereExists(function ($subQuery) {
                        $subQuery->selectRaw('1')
                            ->from('cards as parent_cards')
                            ->whereColumn('parent_cards.id', 'cards.parent_id');
                    });
            })
            ->when($excludeCardId, fn ($q) => $q->where('id', '!=', $excludeCardId))
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    private function collectDescendantCardIds(int $rootCardId): array
    {
        $ids = [];
        $stack = [$rootCardId];

        while ($stack !== []) {
            $currentId = array_pop($stack);
            if (in_array($currentId, $ids, true)) {
                continue;
            }

            $ids[] = $currentId;

            $childIds = Card::query()
                ->where('parent_id', $currentId)
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->all();

            foreach ($childIds as $childId) {
                if (!in_array($childId, $ids, true)) {
                    $stack[] = $childId;
                }
            }
        }

        return $ids;
    }

    private function isCardInDescendantTree(int $cardId, int $candidateParentId): bool
    {
        $visited = [];
        $currentId = $candidateParentId;

        while ($currentId) {
            if ($currentId === $cardId) {
                return true;
            }

            if (in_array($currentId, $visited, true)) {
                return true;
            }

            $visited[] = $currentId;

            $nextParentId = Card::query()
                ->whereKey($currentId)
                ->value('parent_id');

            $currentId = $nextParentId ? (int) $nextParentId : 0;
        }

        return false;
    }

    private function resequenceScope(?int $parentId, ?int $movingCardId = null, ?int $requestedPosition = null): void
    {
        $cardIds = Card::query()
            ->when(
                $parentId === null,
                fn ($query) => $query->whereNull('parent_id'),
                fn ($query) => $query->where('parent_id', $parentId)
            )
            ->when($movingCardId !== null, fn ($query) => $query->where('id', '!=', $movingCardId))
            ->orderByRaw('shape_number IS NULL')
            ->orderBy('shape_number')
            ->orderBy('id')
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();

        if ($movingCardId !== null) {
            $maxPosition = count($cardIds) + 1;
            $targetPosition = $requestedPosition ?? $maxPosition;
            $targetPosition = max(1, min($targetPosition, $maxPosition));
            array_splice($cardIds, $targetPosition - 1, 0, [$movingCardId]);
        }

        foreach ($cardIds as $index => $cardId) {
            Card::query()->whereKey($cardId)->update([
                'shape_number' => $index + 1,
            ]);
        }
    }

    private function getNextShapeNumber(?int $parentId): int
    {
        $maxShapeNumber = Card::query()
            ->when(
                $parentId === null,
                fn ($query) => $query->whereNull('parent_id'),
                fn ($query) => $query->where('parent_id', $parentId)
            )
            ->max('shape_number');

        return ($maxShapeNumber ? (int) $maxShapeNumber : 0) + 1;
    }

    private function getNextShapeNumbersByParent(array $parentIds): array
    {
        $nextShapeNumbersByParent = [
            'root' => $this->getNextShapeNumber(null),
        ];

        foreach ($parentIds as $parentId) {
            $nextShapeNumbersByParent[(string) $parentId] = $this->getNextShapeNumber((int) $parentId);
        }

        return $nextShapeNumbersByParent;
    }
}
