<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use App\Enums\ContentStatus;

class Content extends Entity
{
    protected $datamap = [];
    
    protected $dates = ['created_at', 'updated_at'];
    
    protected $casts = [
        'id' => 'integer',
        'content_type_id' => 'integer',
        'status' => 'enum[' . ContentStatus::class . ']',
    ];

    protected $attributes = [
        'id' => null,
        'content_type_id' => null,
        'title' => null,
        'slug' => null,
        'status' => ContentStatus::DRAFT,
        'created_at' => null,
        'updated_at' => null,
    ];

    public function getStatusBadge(): string
    {
        return $this->status instanceof ContentStatus ? $this->status->badge() : '';
    }

    public function getStatusLabel(): string
    {
        return $this->status instanceof ContentStatus ? $this->status->label() : '';
    }

    public function isPublished(): bool
    {
        return $this->status === ContentStatus::PUBLISHED;
    }

    public function isDraft(): bool
    {
        return $this->status === ContentStatus::DRAFT;
    }

    public function isArchived(): bool
    {
        return $this->status === ContentStatus::ARCHIVED;
    }
}