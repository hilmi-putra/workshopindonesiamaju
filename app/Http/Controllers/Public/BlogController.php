<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        $categories = BlogCategory::orderBy('name')->get();
        $activeCategory = $request->query('category');
        $activeTag = $request->query('tag');

        $query = BlogPost::with(['category', 'author', 'tags'])
            ->where('status', 'published');

        if ($activeCategory) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $activeCategory));
        }

        if ($activeTag) {
            $query->whereHas('tags', fn ($q) => $q->where('slug', $activeTag));
        }

        $heroPost = (clone $query)->orderByDesc('is_featured')->orderByDesc('publish_date')->first();
        $posts = $query->orderByDesc('publish_date')->orderByDesc('id')->paginate(8)->withQueryString();

        $popularPosts = BlogPost::with(['category', 'author'])
            ->where('status', 'published')
            ->orderByDesc('view_count')
            ->take(4)
            ->get();

        $tags = BlogTag::orderBy('name')->get();

        return view('public.blog.index', compact(
            'categories',
            'tags',
            'heroPost',
            'posts',
            'popularPosts',
            'activeCategory',
            'activeTag'
        ));
    }

    public function show(string $slug): View
    {
        $post = BlogPost::with(['category', 'author', 'tags', 'cta'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $post->increment('view_count');

        $relatedPosts = BlogPost::with(['category', 'author'])
            ->where('status', 'published')
            ->where('id', '!=', $post->id)
            ->when($post->blog_category_id, fn ($q) => $q->where('blog_category_id', $post->blog_category_id))
            ->orderByDesc('publish_date')
            ->take(3)
            ->get();

        return view('public.blog.show', compact('post', 'relatedPosts'));
    }
}
