<?php

namespace App\Http\Controllers\Admin;

use App\Exports\NewsletterSubscribersExport;
use App\Http\Controllers\Controller;
use App\Models\NewsletterMessage;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class NewsletterController extends Controller
{
    public function index(): View
    {
        $subscribers = NewsletterSubscriber::latest()->paginate(20);
        $messages = NewsletterMessage::latest()->take(10)->get();

        return view('admin.newsletter.index', compact('subscribers', 'messages'));
    }

    public function broadcast(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ]);

        $activeCount = NewsletterSubscriber::where('status', 'subscribed')->count();

        $msg = NewsletterMessage::create([
            'subject' => $validated['subject'],
            'content' => $validated['content'],
            'sent_at' => now(),
            'total_sent' => $activeCount,
            'status' => 'sent',
        ]);

        Log::info('Newsletter broadcast dicatat', ['id' => $msg->id, 'total_sent' => $activeCount]);

        return back()->with('success', "Pesan newsletter dicatat/dikirim untuk {$activeCount} subscriber aktif.");
    }

    public function destroySubscriber(NewsletterSubscriber $subscriber): RedirectResponse
    {
        $subscriber->delete();
        return back()->with('success', 'Subscriber dihapus.');
    }

    public function export()
    {
        $subscribers = NewsletterSubscriber::latest()->get();
        return Excel::download(new NewsletterSubscribersExport($subscribers), 'newsletter_subscribers.xlsx');
    }
}
