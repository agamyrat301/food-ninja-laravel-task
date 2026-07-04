<?php

namespace App\Http\Controllers;

use App\Actions\Links\CreateLinkForUser;
use App\Http\Requests\StoreLinkRequest;
use App\Models\Link;
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
    public function store(StoreLinkRequest $request, CreateLinkForUser $createLinkForUser)
    {
        $createLinkForUser(Auth::user(), $request->validated('original_url'));

        return redirect()->route('links.index')->with('status', 'Ссылка успешно создана!');
    }

    /**
     * Display click statistics for the specified link.
     */
    public function show(Link $link)
    {
        $this->authorize('view', $link);

        $clicks = $link->clicks()->latest()->paginate(20);

        return view('links.show', compact('link', 'clicks'));
    }

    /**
     * Remove the specified link from storage.
     */
    public function destroy(Link $link)
    {
        $this->authorize('delete', $link);

        $link->delete();

        return redirect()->route('links.index')->with('status', 'Ссылка удалена.');
    }
}
