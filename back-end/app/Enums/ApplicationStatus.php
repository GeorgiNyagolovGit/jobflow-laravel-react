<?php

namespace App\Enums;

enum ApplicationStatus: string
{
    case PENDING = 'pending';
    case REVIEWING = 'reviewing';
    case SHORTLISTED = 'shortlisted';
    case REJECTED = 'rejected';
    case ACCEPTED = 'accepted';
    case WITHDRAWN = 'withdrawn';
}