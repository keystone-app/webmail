import { render, screen } from '@testing-library/svelte';
import ReadingPane from './ReadingPane.svelte';
import { expect, test } from 'vitest';

const mockEmail = {
    id: 1,
    from: 'sender@example.com',
    to: 'me@example.com',
    subject: 'Refined Message View',
    date: '2026-03-05 10:00:00',
    body: '<p>This is a <strong>polished</strong> email body.</p>',
    is_read: true
};

test('reading pane has high-contrast subject', () => {
    render(ReadingPane, { selectedEmail: mockEmail });
    
    const subject = screen.getByText(/Refined Message View/i);
    expect(subject).toHaveClass('text-gray-950');
    expect(subject).toHaveClass('font-extrabold');
});

test('reading pane has action buttons', () => {
    render(ReadingPane, { selectedEmail: mockEmail });
    
    // We'll look for specific action buttons that should be added
    const replyBtn = screen.getByLabelText(/Reply/i);
    expect(replyBtn).toBeInTheDocument();
});
