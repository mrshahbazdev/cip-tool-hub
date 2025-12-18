<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display a custom dynamic page.
     */
    public function show(Page $page)
    {
        if (!$page->is_visible) {
            abort(404);
        }

        return view('pages.show', compact('page'));
    }
}