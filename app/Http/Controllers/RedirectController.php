<?php

namespace App\Http\Controllers;

use App\Events\LinkVisited;
use App\Models\Link;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    public function __invoke(Request $request, string $code)
    {
        $link = Link::where('code', $code)->firstOrFail();

        LinkVisited::dispatch($link, $request->ip(), $request->userAgent());

        return redirect()->away($link->original_url);
    }
}
