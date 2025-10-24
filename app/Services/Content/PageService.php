<?php

namespace App\Services\Content;

use App\Models\Backend\Page;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class PageService
{
    public function paginate(?string $term = null): LengthAwarePaginator
    {
        return Page::query()
            ->when($term, function ($query) use ($term) {
                $query->where(function ($subQuery) use ($term) {
                    $subQuery->where('title', 'like', "%{$term}%")
                        ->orWhere('slug', 'like', "%{$term}%");
                });
            })
            ->latest()
            ->paginate(10);
    }

    public function create(array $data): Page
    {
        $payload = $this->prepare($data);

        return Page::create($payload);
    }

    public function update(Page $page, array $data): Page
    {
        $payload = $this->prepare($data, $page);

        $page->update($payload);

        return $page->refresh();
    }

    public function delete(Page $page): void
    {
        $page->delete();
    }

    protected function prepare(array $data, ?Page $page = null): array
    {
        $data['slug'] = Str::slug($data['slug'] ?? $data['title']);
        $data['is_published'] = $data['is_published'] ?? false;

        if ($data['is_published'] && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        if (!$data['is_published']) {
            $data['published_at'] = null;
        }

        return $data;
    }
}
