<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Card;

class CardController extends Controller
{
    public function show(Card $card)
    {
        return redirect()->route('cards.open', $card);
    }

    public function open(Card $card)
    {
        $currentPath = "/cards/{$card->id}/open";

        if ($card->require_login && auth()->guest()) {
            return redirect()->route('user.login', ['next' => $currentPath]);
        }

        if (($card->destination_type ?? 'url') === 'folder') {
            return redirect('/');
        }

        if (!$card->link_url) {
            return redirect('/');
        }

        return redirect()->away($card->link_url);
    }
}
