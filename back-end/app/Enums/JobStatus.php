<?php

namespace App\Enums;

enum JobStatus: string
{
    case DRAFT = 'draft';
    case PENDING = 'pending';
    case PUBLISHED = 'published';
    case REJECTED = 'rejected';
    case CLOSED = 'closed';
}