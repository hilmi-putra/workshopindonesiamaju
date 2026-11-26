<?php

namespace App\Services\Admin;

use App\Models\Publisher;
use Illuminate\Support\Str;

class PublisherService
{
    /**
     * Get paginated list of publishers with optional search.
     */
    public function getPublishers(int $perPage, ?string $search)
    {
        $query = Publisher::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Create a new publisher.
     */
    public function createPublisher(array $data): Publisher
    {
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Ensure uniqueness for auto-generated slug
        $originalSlug = $data['slug'];
        $counter = 1;
        while (Publisher::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        return Publisher::create($data);
    }

    /**
     * Update an existing publisher.
     */
    public function updatePublisher(Publisher $publisher, array $data): Publisher
    {
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Ensure uniqueness for auto-generated slug
        $originalSlug = $data['slug'];
        $counter = 1;
        while (Publisher::where('slug', $data['slug'])->where('id', '!=', $publisher->id)->exists()) {
            $data['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        $publisher->update($data);

        return $publisher;
    }

    /**
     * Delete a publisher.
     */
    public function deletePublisher(Publisher $publisher): void
    {
        $publisher->delete();
    }
}
