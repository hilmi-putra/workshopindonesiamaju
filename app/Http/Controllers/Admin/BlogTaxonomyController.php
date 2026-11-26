<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogAuthor;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BlogTaxonomyController extends Controller
{
    public function index(): View
    {
        return view('admin.blog.taxonomy', [
            'categories' => BlogCategory::withCount('posts')->orderBy('name')->get(),
            'tags' => BlogTag::withCount('posts')->orderBy('name')->get(),
            'authors' => BlogAuthor::withCount('posts')->orderBy('name')->get(),
        ]);
    }

    public function storeCategory(Request $request): RedirectResponse
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:150'], 'description' => ['nullable', 'string']]);
        $data['slug'] = Str::slug($data['name']) . '-' . Str::random(4);
        BlogCategory::create($data);
        return back()->with('success', 'Kategori blog ditambahkan.');
    }

    public function destroyCategory(BlogCategory $category): RedirectResponse
    {
        $category->delete();
        return back()->with('success', 'Kategori blog dihapus.');
    }

    public function storeTag(Request $request): RedirectResponse
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:150']]);
        $data['slug'] = Str::slug($data['name']) . '-' . Str::random(4);
        BlogTag::create($data);
        return back()->with('success', 'Tag blog ditambahkan.');
    }

    public function destroyTag(BlogTag $tag): RedirectResponse
    {
        $tag->delete();
        return back()->with('success', 'Tag blog dihapus.');
    }

    public function storeAuthor(Request $request): RedirectResponse
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:150'], 'bio' => ['nullable', 'string']]);
        $data['slug'] = Str::slug($data['name']) . '-' . Str::random(4);
        BlogAuthor::create($data);
        return back()->with('success', 'Author blog ditambahkan.');
    }

    public function destroyAuthor(BlogAuthor $author): RedirectResponse
    {
        $author->delete();
        return back()->with('success', 'Author blog dihapus.');
    }
}
