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
            'imap_uid' => $this->imap_uid,
            'folder' => $this->folder,
            'subject' => $this->subject,
            'from' => $this->from,
            'to' => $this->to,
            'date' => $this->date->toIso8601String(),
            'body' => $this->when($request->route('email'), $this->body),
            'body_excerpt' => Str::limit(strip_tags($this->body), 100),
            'is_read' => $this->is_read,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
