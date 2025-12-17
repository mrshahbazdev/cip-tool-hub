<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubdomainController extends Controller
{
    /**
     * Check if subdomain is available
     * No authentication required - public endpoint
     */
    public function checkAvailability(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'subdomain' => ['required', 'string', 'min:3', 'max:63', 'regex:/^[a-z0-9]([a-z0-9-]{0,61}[a-z0-9])?$/'],
            'tool_id' => ['required', 'exists:tools,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'available' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $subdomain = strtolower($request->subdomain);
        $toolId = $request->tool_id;

        // Check if subdomain already exists
        $exists = Subscription::where('subdomain', $subdomain)
            ->whereHas('package', function ($query) use ($toolId) {
                $query->where('tool_id', $toolId);
            })
            ->exists();

        if ($exists) {
            return response()->json([
                'available' => false,
                'message' => 'This subdomain is already taken.',
            ]);
        }

        // Reserved subdomains
        $reserved = [
            'www', 'admin', 'api', 'mail', 'ftp', 'smtp', 'pop', 'imap',
            'ns1', 'ns2', 'cpanel', 'webmail', 'localhost', 'app',
            'dashboard', 'blog', 'shop', 'store', 'support', 'help',
            'forum', 'community', 'docs', 'wiki', 'cdn', 'static',
            'assets', 'images', 'files', 'download', 'upload'
        ];
        
        if (in_array($subdomain, $reserved)) {
            return response()->json([
                'available' => false,
                'message' => 'This subdomain is reserved and cannot be used.',
            ]);
        }

        return response()->json([
            'available' => true,
            'message' => 'Great! This subdomain is available.',
            'preview' => $subdomain . '.' . \App\Models\Tool::find($toolId)->domain,
        ]);
    }
}