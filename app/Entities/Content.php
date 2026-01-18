<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use App\Enums\ContentStatus;

class Content extends Entity
{
    protected $datamap = [];
    protected $dates = ['created_at', 'updated_at'];
    protected $casts = [
        'content_type_id' => 'int',
        'status' => 'enum[' . ContentStatus::class . ']',
    ];

    public function getStatusBadge(): string
    {
        return $this->status->badge();
    }

    public function getStatusLabel(): string
    {
        return $this->status->label();
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
