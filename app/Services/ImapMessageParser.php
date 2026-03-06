<?php

namespace App\Services;

use Webklex\PHPIMAP\Message;

class ImapMessageParser
{
    /**
     * Parse the body of the message, preferring HTML.
     *
     * @param Message $message
     * @return string
     */
    public function parseBody(Message $message): string
    {
        return $message->getHTMLBody() ?: (string)$message->getTextBody();
    }

    /**
     * Parse basic details from the message.
     *
     * @param Message $message
     * @return array
     */
    public function parseDetails(Message $message): array
    {
        return [
            'imap_uid' => $message->uid,
            'subject' => (string)$message->subject,
            'from' => $message->from->first()->mail ?? '',
            'to' => $message->to->first()->mail ?? '',
            'date' => $message->date,
            'is_read' => $message->getFlags()->has('seen') ? 1 : 0,
        ];
    }

    /**
     * Extract attachments from the message.
     *
     * @param Message $message
     * @return array
     */
    public function extractAttachments(Message $message): array
    {
        $attachments = [];
        $imapAttachments = $message->getAttachments();

        foreach ($imapAttachments as $attachment) {
            $attachments[] = [
                'filename' => $attachment->getName() ?: 'unnamed_attachment',
                'content_type' => $attachment->getContentType(),
                'size' => $attachment->getSize(),
                'content_id' => $attachment->getContentId(),
                'content' => $attachment->getContent(),
                'is_inline' => strtolower((string)$attachment->disposition) === 'inline',
                'disposition' => $attachment->disposition,
            ];
        }

        return $attachments;
    }
}
