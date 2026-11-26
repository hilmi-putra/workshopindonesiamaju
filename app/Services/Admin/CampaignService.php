<?php

namespace App\Services\Admin;

use App\Models\Campaign;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class CampaignService
{
    public function getPaginated(int $perPage, ?string $search, ?string $status): LengthAwarePaginator
    {
        $query = Campaign::with(['books', 'categories']);

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
        }
        
        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        return $query->orderBy('priority', 'desc')->latest()->paginate($perPage);
    }

    public function create(array $data): Campaign
    {
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        if (isset($data['banner_desktop']) && $data['banner_desktop'] instanceof \Illuminate\Http\UploadedFile) {
            $data['banner_desktop'] = '/storage/' . $data['banner_desktop']->store('campaigns', 'public');
        }

        if (isset($data['banner_mobile']) && $data['banner_mobile'] instanceof \Illuminate\Http\UploadedFile) {
            $data['banner_mobile'] = '/storage/' . $data['banner_mobile']->store('campaigns', 'public');
        }

        return DB::transaction(function () use ($data) {
            $campaign = Campaign::create($data);

            if (isset($data['books']) && is_array($data['books'])) {
                $campaign->books()->sync($data['books']);
            }

            if (isset($data['categories']) && is_array($data['categories'])) {
                $campaign->categories()->sync($data['categories']);
            }

            return $campaign;
        });
    }

    public function update(Campaign $campaign, array $data): Campaign
    {
        if (isset($data['name']) && empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        if (isset($data['banner_desktop']) && $data['banner_desktop'] instanceof \Illuminate\Http\UploadedFile) {
            if ($campaign->banner_desktop && str_starts_with($campaign->banner_desktop, '/storage/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $campaign->banner_desktop));
            }
            $data['banner_desktop'] = '/storage/' . $data['banner_desktop']->store('campaigns', 'public');
        }

        if (isset($data['banner_mobile']) && $data['banner_mobile'] instanceof \Illuminate\Http\UploadedFile) {
            if ($campaign->banner_mobile && str_starts_with($campaign->banner_mobile, '/storage/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $campaign->banner_mobile));
            }
            $data['banner_mobile'] = '/storage/' . $data['banner_mobile']->store('campaigns', 'public');
        }

        return DB::transaction(function () use ($campaign, $data) {
            $campaign->update($data);

            if (isset($data['books']) && is_array($data['books'])) {
                $campaign->books()->sync($data['books']);
            }

            if (isset($data['categories']) && is_array($data['categories'])) {
                $campaign->categories()->sync($data['categories']);
            }

            return $campaign;
        });
    }

    public function delete(Campaign $campaign): void
    {
        if ($campaign->banner_desktop && str_starts_with($campaign->banner_desktop, '/storage/')) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $campaign->banner_desktop));
        }
        if ($campaign->banner_mobile && str_starts_with($campaign->banner_mobile, '/storage/')) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $campaign->banner_mobile));
        }
        
        $campaign->delete();
    }
}
