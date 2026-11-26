<?php

namespace App\Services\Admin;

use App\Models\NewsletterSubscriber;
use App\Models\NewsletterMessage;
use Illuminate\Pagination\LengthAwarePaginator;

class NewsletterService
{
    public function getSubscribers(int $perPage, ?string $search, ?string $status): LengthAwarePaginator
    {
        $query = NewsletterSubscriber::query();

        if ($search) {
            $query->where('email', 'like', "%{$search}%");
        }
        
        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        return $query->latest()->paginate($perPage);
    }

    public function getMessages(int $perPage, ?string $search): LengthAwarePaginator
    {
        $query = NewsletterMessage::query();

        if ($search) {
            $query->where('subject', 'like', "%{$search}%");
        }

        return $query->latest()->paginate($perPage);
    }

    public function createSubscriber(array $data): NewsletterSubscriber
    {
        return NewsletterSubscriber::firstOrCreate(
            ['email' => $data['email']],
            ['status' => $data['status'] ?? 'subscribed']
        );
    }

    public function updateSubscriber(NewsletterSubscriber $subscriber, array $data): NewsletterSubscriber
    {
        $subscriber->update($data);
        return $subscriber;
    }

    public function deleteSubscriber(NewsletterSubscriber $subscriber): void
    {
        $subscriber->delete();
    }

    public function createMessage(array $data): NewsletterMessage
    {
        return NewsletterMessage::create($data);
    }

    public function broadcastMessage(NewsletterMessage $message): void
    {
        // Dummy broadcast implementation. In real app, dispatch Job to send emails.
        $message->update([
            'status' => 'sent',
            'sent_at' => now(),
            'total_sent' => NewsletterSubscriber::where('status', 'subscribed')->count(),
        ]);
    }
}
