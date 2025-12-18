<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of published blog posts.
     */
    public function index(Request $request)
    {
        $query = Post::with(['category', 'user'])
            ->where('is_published', true)
            ->where('published_at', '<=', now())
            ->latest('published_at');

        // Optional: Filter by category if provided in query string
        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $posts = $query->paginate(9);
        $categories = Category::withCount('posts')->get();

        return view('blog.index', compact('posts', 'categories'));
    }

    /**
     * Display the specified blog post.
     */
    public function show(Post $post)
    {
        // Ensure post is published before showing
        if (!$post->is_published || $post->published_at > now()) {
            abort(404);
        }

        // Load related data
        $post->load(['category', 'user']);

        // Fetch related posts from same category
        $relatedPosts = Post::where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->where('is_published', true)
            ->limit(3)
            ->get();

        return view('blog.show', compact('post', 'relatedPosts'));
    }
}