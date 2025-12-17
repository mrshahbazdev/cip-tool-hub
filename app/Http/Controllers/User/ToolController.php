<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ToolController extends Controller
{
    /**
     * Display listing of all active tools with search
     */
    public function index(Request $request): View
    {
        $search = $request->get('search');

        $tools = Tool::with(['packages' => function ($query) {
            $query->where('status', true)->orderBy('price');
        }])
            ->where('status', true)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('domain', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(12);

        return view('user.tools.index', compact('tools'));
    }

    /**
     * Display specific tool with all packages
     */
    public function show(Tool $tool): View
    {
        if (!$tool->status) {
            abort(404, 'Tool not found');
        }

        $tool->load(['packages' => function ($query) {
            $query->where('status', true)->orderBy('price');
        }]);

        // Check if user has active subscription for this tool
        $hasActiveSubscription = false;
        if (auth()->check()) {
            $hasActiveSubscription = auth()->user()
                ->activeSubscriptions()
                ->whereHas('package', function ($query) use ($tool) {
                    $query->where('tool_id', $tool->id);
                })
                ->exists();
        }

        return view('user.tools.show', compact('tool', 'hasActiveSubscription'));
    }
}