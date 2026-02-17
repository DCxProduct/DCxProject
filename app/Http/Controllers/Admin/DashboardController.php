<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Card;


class DashboardController extends Controller
{
    public function index()
    {
        $query = request('q');

        $cards = Card::query()
            ->when($query, function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%');
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        if (request()->ajax()) {
            return view('admins.partials.cards', compact('cards'))->render();
        }

        return view('admins.dashboard', compact('cards', 'query'));
    }
    
}
