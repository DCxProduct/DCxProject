<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Card;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->query('q');
        $folderId = $request->query('folder');
        $folderCard = null;
        $isFolderView = false;
        $user = $request->user();

        $configuredAdminEmail = (string) config('app.admin_email');
        $isAdmin = $user
            && (
                (int) $user->id === 1
                || strtolower((string) $user->name) === 'admin'
                || ($configuredAdminEmail !== '' && strtolower((string) $user->email) === strtolower($configuredAdminEmail))
            );

        $cardsQuery = Card::query();
        $isAjaxRequest = $request->ajax();

        if ($folderId && !$isAjaxRequest) {
            return redirect('/');
        }

        if ($folderId && $isAjaxRequest) {
            $folderCard = Card::query()->find($folderId);

            if (!$folderCard || ($folderCard->destination_type ?? 'url') !== 'folder') {
                return response('<div class="alert alert-info mb-0">No Applications found!</div>', 200);
            }

            if ($folderCard->require_login && auth()->guest()) {
                return response('<div class="alert alert-warning mb-0">Please login first to open this Application!</div>', 200);
            }

            $cardsQuery->where('parent_id', $folderCard->id);
            $isFolderView = true;
        } else {
            $cardsQuery->whereNull('parent_id');
        }

        $cards = $cardsQuery
            ->when($query, function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%');
            })
            ->orderByRaw('shape_number IS NULL')
            ->orderBy('shape_number')
            ->latest('id')
            ->paginate(16)
            ->withQueryString();

        if ($request->ajax()) {
            return view('public.partials.cards', compact('cards', 'isAdmin', 'isFolderView'))->render();
        }

        return view('public.home', compact('cards', 'query', 'isAdmin', 'folderCard', 'isFolderView'));
    }

    public function projectDetail(string $slug)
    {
        return view('public.project-detail', compact('slug'));
    }
}
