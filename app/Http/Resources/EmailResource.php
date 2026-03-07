<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class EmailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uid' => $this->uid, // Renamed from imap_uid
            'account_id' => $this->account_id,
            'message_id' => $this->message_id,
            'folder' => $this->folder,
            'subject' => $this->subject,
            'from_email' => $this->from_email,
            'sender_name' => $this->sender_name,
            'recipients' => $this->recipients,
            'sent_at' => $this->sent_at->toIso8601String(), // Renamed from date
            'is_seen' => $this->is_seen, // Renamed from is_read
            'has_attachments' => $this->has_attachments,
            'thread_id' => $this->thread_id,
            'attachments' => $this->attachments->map(function ($attachment) {
                return [
                    'id' => $attachment->id,
                    'filename' => $attachment->filename,
                    'content_type' => $attachment->content_type,
                    'size' => $attachment->size,
                    'url' => route('attachments.show', $attachment->id),
                    'is_inline' => $attachment->is_inline,
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
