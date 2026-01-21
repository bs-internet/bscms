<?php

namespace App\Core\Modules\Form\Entities;

use CodeIgniter\Entity\Entity;
use App\Core\Modules\Form\Enums\SubmissionStatus;

class FormSubmission extends Entity
{
    protected $datamap = [];
    
    protected $dates = ['created_at'];
    
    protected $casts = [
        'id' => 'integer',
        'form_id' => 'integer',
        'status' => 'enum[' . SubmissionStatus::class . ']',
    ];

    protected $attributes = [
        'id' => null,
        'form_id' => null,
        'ip_address' => null,
        'user_agent' => null,
        'status' => SubmissionStatus::NEW,
        'created_at' => null,
    ];

    public function getStatusBadge(): string
    {
        return $this->status instanceof SubmissionStatus ? $this->status->badge() : '';
    }

    public function getStatusLabel(): string
    {
        return $this->status instanceof SubmissionStatus ? $this->status->label() : '';
    }

    public function isNew(): bool
    {
        return $this->status === SubmissionStatus::NEW;
    }

    public function isRead(): bool
    {
        return $this->status === SubmissionStatus::READ;
    }

    public function markAsRead(): void
    {
        $this->status = SubmissionStatus::READ;
    }
}

