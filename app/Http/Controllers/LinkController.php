<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LinkController extends Controller
{
    /**
     * Display a listing of the user's links.
     */
    public function index()
    {
        $links = Auth::user()
            ->links()
            ->withCount('clicks')
            ->latest()
            ->get();

        return view('links.index', compact('links'));
    }

    /**
     * Store a newly created link in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'original_url' => ['required', 'url', 'max:2048'],
        ]);

        Auth::user()->links()->create([
            'original_url' => $validated['original_url'],
        ]);

        return redirect()->route('links.index')->with('status', 'Ссылка успешно создана!');
    }

    /**
     * Display click statistics for the specified link.
     */
    public function show(Link $link)
    {
        abort_unless($link->user_id === Auth::id(), 403);

        $clicks = $link->clicks()->latest()->paginate(20);

        return view('links.show', compact('link', 'clicks'));
    }

    /**
     * Remove the specified link from storage.
     */
    public function destroy(Link $link)
    {
        abort_unless($link->user_id === Auth::id(), 403);

        $link->delete();

        return redirect()->route('links.index')->with('status', 'Ссылка удалена.');
    }
}
