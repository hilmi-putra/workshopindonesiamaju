{!! '<' . '?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url><loc>{{ route('home') }}</loc><changefreq>weekly</changefreq><priority>1.0</priority></url>
    <url><loc>{{ route('bookstore.index') }}</loc><changefreq>daily</changefreq><priority>0.9</priority></url>
    <url><loc>{{ route('blog.index') }}</loc><changefreq>daily</changefreq><priority>0.8</priority></url>
    @foreach($books as $book)
        <url>
            <loc>{{ route('bookstore.show', $book->slug) }}</loc>
            <lastmod>{{ optional($book->updated_at)->toAtomString() }}</lastmod>
            <priority>0.8</priority>
        </url>
    @endforeach
    @foreach($posts as $post)
        <url>
            <loc>{{ route('blog.show', $post->slug) }}</loc>
            <lastmod>{{ optional($post->updated_at)->toAtomString() }}</lastmod>
            <priority>0.7</priority>
        </url>
    @endforeach
</urlset>
