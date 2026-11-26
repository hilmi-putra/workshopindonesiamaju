<?php

namespace App\Services\Admin;

use App\Models\Banner;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class BannerService
{
    public function getPaginated(int $perPage, ?string $search): LengthAwarePaginator
    {
        $query = Banner::query();

        if ($search) {
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('subtitle', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        return $query->orderBy('sort_order', 'asc')->orderBy('id', 'desc')->paginate($perPage);
    }

    public function create(array $data): Banner
    {
        if (isset($data['image_desktop']) && $data['image_desktop'] instanceof \Illuminate\Http\UploadedFile) {
            $data['image_desktop'] = '/storage/' . $data['image_desktop']->store('banners', 'public');
        }

        if (isset($data['image_mobile']) && $data['image_mobile'] instanceof \Illuminate\Http\UploadedFile) {
            $data['image_mobile'] = '/storage/' . $data['image_mobile']->store('banners', 'public');
        }

        return Banner::create($data);
    }

    public function update(Banner $banner, array $data): Banner
    {
        if (isset($data['image_desktop']) && $data['image_desktop'] instanceof \Illuminate\Http\UploadedFile) {
            if ($banner->image_desktop && str_starts_with($banner->image_desktop, '/storage/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $banner->image_desktop));
            }
            $data['image_desktop'] = '/storage/' . $data['image_desktop']->store('banners', 'public');
        }

        if (isset($data['image_mobile']) && $data['image_mobile'] instanceof \Illuminate\Http\UploadedFile) {
            if ($banner->image_mobile && str_starts_with($banner->image_mobile, '/storage/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $banner->image_mobile));
            }
            $data['image_mobile'] = '/storage/' . $data['image_mobile']->store('banners', 'public');
        }

        $banner->update($data);
        return $banner;
    }

    public function delete(Banner $banner): void
    {
        if ($banner->image_desktop && str_starts_with($banner->image_desktop, '/storage/')) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $banner->image_desktop));
        }
        if ($banner->image_mobile && str_starts_with($banner->image_mobile, '/storage/')) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $banner->image_mobile));
        }
        
        $banner->delete();
    }
}
