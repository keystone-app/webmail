import { render, screen } from '@testing-library/svelte';
import EmailList from './EmailList.svelte';
import { expect, test } from 'vitest';

const mockEmails = [
    {
        id: 1,
        from: 'sender@example.com',
        subject: 'Important Update',
        date: '2026-03-05',
        body_excerpt: 'Please review the attached document...',
        is_read: false
    }
];

test('email list item has high-contrast sender name', () => {
    render(EmailList, { emails: mockEmails, isLoading: false });
    
    const sender = screen.getByText(/sender@example.com/i);
    expect(sender).toHaveClass('text-gray-950');
    expect(sender).toHaveClass('font-bold');
});

test('email list item shows contextual actions', () => {
    render(EmailList, { emails: mockEmails, isLoading: false });
    
    // Check for presence of action buttons (Archive/Delete placeholders)
    const archiveBtn = screen.getByLabelText(/Archive/i);
    const deleteBtn = screen.getByLabelText(/Delete/i);
    
    expect(archiveBtn).toBeInTheDocument();
    expect(deleteBtn).toBeInTheDocument();
});
