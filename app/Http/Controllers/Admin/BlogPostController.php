<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogAuthor;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BlogPostController extends Controller
{
    public function index(Request $request): View
    {
        $query = BlogPost::with(['category', 'author', 'tags']);

        if ($request->filled('search')) {
            $s = trim((string) $request->search);
            $query->where('title', 'like', "%{$s}%");
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $posts = $query->latest()->paginate(15)->withQueryString();

        return view('admin.blog.posts.index', compact('posts'));
    }

    public function create(): View
    {
        return view('admin.blog.posts.form', [
            'post' => new BlogPost(['status' => 'published']),
            'categories' => BlogCategory::orderBy('name')->get(),
            'authors' => BlogAuthor::orderBy('name')->get(),
            'tags' => BlogTag::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePost($request);
        $validated['slug'] = $this->uniqueSlug($validated['title']);

        if ($request->hasFile('featured_image_file')) {
            $path = asset('storage/' . $request->file('featured_image_file')->store('blog', 'public'));
            $validated['featured_image'] = $path;
            $validated['thumbnail'] = $path;
        }

        $post = BlogPost::create($validated);
        $post->tags()->sync($request->input('tags', []));

        return redirect()->route('admin.blog.posts.index')->with('success', 'Artikel blog berhasil ditambahkan.');
    }

    public function edit(BlogPost $post): View
    {
        $post->load('tags');

        return view('admin.blog.posts.form', [
            'post' => $post,
            'categories' => BlogCategory::orderBy('name')->get(),
            'authors' => BlogAuthor::orderBy('name')->get(),
            'tags' => BlogTag::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, BlogPost $post): RedirectResponse
    {
        $validated = $this->validatePost($request);

        if ($request->hasFile('featured_image_file')) {
            $path = asset('storage/' . $request->file('featured_image_file')->store('blog', 'public'));
            $validated['featured_image'] = $path;
            $validated['thumbnail'] = $path;
        }

        $post->update($validated);
        $post->tags()->sync($request->input('tags', []));

        return redirect()->route('admin.blog.posts.index')->with('success', 'Artikel blog berhasil diperbarui.');
    }

    public function destroy(BlogPost $post): RedirectResponse
    {
        $post->delete();
        return redirect()->route('admin.blog.posts.index')->with('success', 'Artikel blog berhasil dihapus.');
    }

    protected function validatePost(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'status' => ['required', 'in:draft,published,scheduled,archived'],
            'blog_category_id' => ['nullable', 'exists:blog_categories,id'],
            'blog_author_id' => ['nullable', 'exists:blog_authors,id'],
            'reading_time' => ['nullable', 'integer', 'min:1'],
            'featured_image' => ['nullable', 'string', 'max:500'],
            'featured_image_file' => ['nullable', 'image', 'max:4096'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
        ]);

        $data['is_featured'] = $request->boolean('is_featured');
        $data['reading_time'] = $data['reading_time'] ?? max(1, (int) ceil(str_word_count(strip_tags($data['content'])) / 200));

        if ($data['status'] === 'published') {
            $data['publish_date'] = $request->input('publish_date') ?: now();
        }

        return $data;
    }

    protected function uniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;
        while (BlogPost::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }
}
