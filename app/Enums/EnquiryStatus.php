<?php

namespace App\Enums;

enum EnquiryStatus: string
{
    case New = 'new';
    case Contacted = 'contacted';
    case InProgress = 'in_progress';
    case Converted = 'converted';
    case Closed = 'closed';
}
