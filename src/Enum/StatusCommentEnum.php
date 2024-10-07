<?php

namespace App\Enum;

enum StatusCommentEnum: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
}
