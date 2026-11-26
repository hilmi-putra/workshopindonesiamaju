<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\ManuscriptSubmissionRequest;
use App\Models\Book;
use App\Models\ManuscriptSubmission;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featuredBooks = Book::with(['author', 'categories'])
            ->published()
            ->orderByDesc('is_featured')
            ->orderByDesc('is_bestseller')
            ->latest('published_at')
            ->take(8)
            ->get();

        return view('public.home', compact('featuredBooks'));
    }

    public function submitManuscript(ManuscriptSubmissionRequest $request): RedirectResponse
    {
        $file = $request->file('manuscript_file');
        $path = $file->store('manuscripts', 'public');

        $submission = ManuscriptSubmission::create([
            'user_id' => Auth::id(),
            'title' => $request->validated('title'),
            'author_name' => $request->validated('author_name'),
            'email' => $request->validated('email'),
            'phone' => $request->validated('phone'),
            'genre' => $request->validated('genre'),
            'synopsis' => $request->validated('synopsis'),
            'file_path' => $path,
            'original_filename' => $file->getClientOriginalName(),
            'status' => ManuscriptSubmission::STATUS_PENDING,
        ]);

        Log::info('Pengajuan naskah baru diterima', [
            'submission_id' => $submission->id,
            'title' => $submission->title,
            'email' => $submission->email,
        ]);

        return redirect()->to(route('home') . '#ajukan-naskah')
            ->with('success', 'Terima kasih! Naskah "' . $submission->title . '" berhasil dikirim dan akan segera dikurasi tim redaksi kami.');
    }

    public function subscribeNewsletter(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $existing = NewsletterSubscriber::where('email', $validated['email'])->first();
        if ($existing) {
            if ($existing->status !== 'subscribed') {
                $existing->update(['status' => 'subscribed', 'subscribed_at' => now()]);
                return back()->with('success', 'Langganan newsletter Anda diaktifkan kembali.');
            }
            return back()->with('success', 'Email Anda sudah terdaftar di newsletter Workshop Indonesia Maju.');
        }

        NewsletterSubscriber::create([
            'email' => $validated['email'],
            'status' => 'subscribed',
            'subscribed_at' => now(),
        ]);

        return back()->with('success', 'Berhasil berlangganan newsletter Workshop Indonesia Maju!');
    }

    public function sitemap(): Response
    {
        $books = Book::published()->select('slug', 'updated_at')->get();
        $posts = \App\Models\BlogPost::where('status', 'published')->select('slug', 'updated_at')->get();

        $xml = view('public.sitemap', compact('books', 'posts'))->render();

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }
}
