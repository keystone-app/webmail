<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MailAccount extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'email',
        'imap_uidvalidity',
        'last_sync_at',
    ];

    protected $casts = [
        'last_sync_at' => 'datetime',
        'imap_uidvalidity' => 'integer',
    ];

    /**
     * Get the user that owns the mail account.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the emails for the mail account.
     */
    public function emails(): HasMany
    {
        return $this->hasMany(Email::class, 'account_id');
    }
}
