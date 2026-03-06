<?php

namespace Tests\Unit\Services;

use App\Services\ImapMessageParser;
use Mockery;
use Tests\TestCase;
use Webklex\PHPIMAP\Message;
use Webklex\PHPIMAP\Support\AttachmentCollection;

class ImapMessageParserTest extends TestCase
{
    public function test_it_can_parse_message_body(): void
    {
        $messageMock = Mockery::mock(Message::class);
        $messageMock->shouldReceive('getHTMLBody')->andReturn('<p>HTML Body</p>');
        $messageMock->shouldReceive('getTextBody')->never();

        $parser = new ImapMessageParser();
        $body = $parser->parseBody($messageMock);

        $this->assertEquals('<p>HTML Body</p>', $body);
    }

    public function test_it_falls_back_to_text_body(): void
    {
        $messageMock = Mockery::mock(Message::class);
        $messageMock->shouldReceive('getHTMLBody')->andReturn('');
        $messageMock->shouldReceive('getTextBody')->andReturn('Text Body');

        $parser = new ImapMessageParser();
        $body = $parser->parseBody($messageMock);

        $this->assertEquals('Text Body', $body);
    }

    public function test_it_can_parse_basic_details(): void
    {
        $messageMock = Mockery::mock(Message::class);
        $messageMock->shouldReceive('get')->with('uid')->andReturn(123);
        $messageMock->shouldReceive('get')->with('subject')->andReturn('Test Subject');
        $messageMock->shouldReceive('get')->with('date')->andReturn('2026-03-06 12:00:00');
        
        $fromMock = Mockery::mock();
        $fromMock->mail = 'sender@example.com';
        $messageMock->shouldReceive('get')->with('from')->andReturn(collect([$fromMock]));

        $toMock = Mockery::mock();
        $toMock->mail = 'receiver@example.com';
        $messageMock->shouldReceive('get')->with('to')->andReturn(collect([$toMock]));

        $flagsMock = Mockery::mock('Webklex\PHPIMAP\Support\FlagCollection');
        $flagsMock->shouldReceive('has')->with('seen')->andReturn(true);
        $messageMock->shouldReceive('getFlags')->andReturn($flagsMock);

        $parser = new ImapMessageParser();
        $details = $parser->parseDetails($messageMock);

        $this->assertEquals(123, $details['imap_uid']);
        $this->assertEquals('Test Subject', $details['subject']);
        $this->assertEquals('sender@example.com', $details['from']);
        $this->assertEquals('receiver@example.com', $details['to']);
        $this->assertEquals(1, $details['is_read']);
    }

    public function test_it_can_extract_attachments(): void
    {
        $attachmentMock = Mockery::mock();
        $attachmentMock->shouldReceive('getName')->andReturn('test.pdf');
        $attachmentMock->shouldReceive('getContentType')->andReturn('application/pdf');
        $attachmentMock->shouldReceive('getSize')->andReturn(1024);
        $attachmentMock->shouldReceive('getContentId')->andReturn('id123');
        $attachmentMock->shouldReceive('getContent')->andReturn('binary content');
        $attachmentMock->disposition = 'attachment';

        $messageMock = Mockery::mock(Message::class);
        $messageMock->shouldReceive('getAttachments')->andReturn(new AttachmentCollection(collect([$attachmentMock])));

        $parser = new ImapMessageParser();
        $attachments = $parser->extractAttachments($messageMock);

        $this->assertCount(1, $attachments);
        $this->assertEquals('test.pdf', $attachments[0]['filename']);
        $this->assertEquals('application/pdf', $attachments[0]['content_type']);
        $this->assertEquals(1024, $attachments[0]['size']);
        $this->assertEquals('id123', $attachments[0]['content_id']);
        $this->assertEquals('binary content', $attachments[0]['content']);
        $this->assertFalse($attachments[0]['is_inline']);
    }
}
