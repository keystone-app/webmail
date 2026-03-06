<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attachment extends Model
{
    /** @use HasFactory<\Database\Factories\AttachmentFactory> */
    use HasFactory;

    protected $fillable = [
        'email_id',
        'filename',
        'content_type',
        'size',
        'path',
        'is_inline',
        'content_id',
    ];

    protected function casts(): array
    {
        return [
            'is_inline' => 'boolean',
        ];
    }

    public function email(): BelongsTo
    {
        return $this->belongsTo(Email::class);
    }
}
