<?php

namespace Tests\Feature;

use App\Livewire\CatalogFilter;
use App\Models\Author;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Book;
use App\Models\Category;
use App\Models\Genre;
use App\Models\Publisher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class PublicAndCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_render_without_login(): void
    {
        $author = Author::factory()->create();
        $publisher = Publisher::factory()->create();
        $book = Book::factory()->create([
            'author_id' => $author->id,
            'publisher_id' => $publisher->id,
            'status' => 'published',
        ]);

        $cat = BlogCategory::create(['name' => 'Literasi', 'slug' => 'literasi']);
        $post = BlogPost::create([
            'title' => 'Tips Menulis Novel',
            'slug' => 'tips-menulis-novel',
            'excerpt' => 'Ringkasan',
            'content' => '<p>Konten artikel</p>',
            'status' => 'published',
            'blog_category_id' => $cat->id,
            'publish_date' => now(),
        ]);

        $this->get(route('home'))->assertOk()->assertSee('Mega Press');
        $this->get(route('bookstore.index'))->assertOk();
        $this->get(route('bookstore.show', $book->slug))->assertOk()->assertSee($book->title);
        $this->get(route('blog.index'))->assertOk()->assertSee($post->title);
        $this->get(route('blog.show', $post->slug))->assertOk()->assertSee($post->title);
        $this->get(route('search', ['q' => $book->title]))->assertOk()->assertSee($book->title);
        $this->get(route('sitemap'))->assertOk();
    }

    public function test_livewire_catalog_filter_searches_and_filters_by_category_and_genre(): void
    {
        $author = Author::factory()->create();
        $publisher = Publisher::factory()->create();
        $catFiksi = Category::factory()->create(['name' => 'Fiksi', 'slug' => 'fiksi']);
        $catAkademik = Category::factory()->create(['name' => 'Akademik', 'slug' => 'akademik']);
        $genreDrama = Genre::factory()->create(['name' => 'Drama', 'slug' => 'drama']);

        $book1 = Book::factory()->create([
            'title' => 'Laskar Senja',
            'author_id' => $author->id,
            'publisher_id' => $publisher->id,
            'status' => 'published',
        ]);
        $book1->categories()->attach($catFiksi);
        $book1->genres()->attach($genreDrama);

        $book2 = Book::factory()->create([
            'title' => 'Pengantar Algoritma',
            'author_id' => $author->id,
            'publisher_id' => $publisher->id,
            'status' => 'published',
        ]);
        $book2->categories()->attach($catAkademik);

        Livewire::test(CatalogFilter::class)
            ->set('search', 'Senja')
            ->assertSee('Laskar Senja')
            ->assertDontSee('Pengantar Algoritma')
            ->set('search', '')
            ->set('category', 'akademik')
            ->assertSee('Pengantar Algoritma')
            ->assertDontSee('Laskar Senja');
    }

    public function test_manuscript_submission_validates_and_stores_file(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('naskah-baru.pdf', 512, 'application/pdf');

        $response = $this->post(route('submissions.store'), [
            'title' => 'Langit Di Atas Nusantara',
            'author_name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'phone' => '081234567890',
            'genre' => 'Novel',
            'synopsis' => 'Ini adalah sinopsis panjang tentang perjalanan seorang penulis muda di Indonesia.',
            'manuscript_file' => $file,
        ]);

        $response->assertRedirect(route('home') . '#ajukan-naskah');
        $this->assertDatabaseHas('manuscript_submissions', [
            'title' => 'Langit Di Atas Nusantara',
            'email' => 'budi@example.com',
            'status' => 'pending',
        ]);
    }

    public function test_newsletter_subscription_is_deduplicated(): void
    {
        $this->post(route('newsletter.subscribe'), ['email' => 'reader@example.com'])
            ->assertRedirect();
        $this->post(route('newsletter.subscribe'), ['email' => 'reader@example.com'])
            ->assertRedirect();

        $this->assertDatabaseCount('newsletter_subscribers', 1);
    }
}
