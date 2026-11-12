<?php

namespace App\Models;

use App\Enums\EnquiryStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'name',
        'mobile',
        'email',
        'product_reference',
        'message',
        'status',
        'internal_notes',
        'follow_up_date',
    ];

    protected $casts = [
        'status' => EnquiryStatus::class,
        'follow_up_date' => 'date',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
