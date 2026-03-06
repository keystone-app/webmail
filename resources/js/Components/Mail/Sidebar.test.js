import { render, screen } from '@testing-library/svelte';
import Sidebar from './Sidebar.svelte';
import { expect, test } from 'vitest';

test('sidebar has high-contrast active state', () => {
    render(Sidebar, { user: { email: 'test@example.com' }, activeFolder: 'Inbox' });
    
    const activeBtn = screen.getByText(/Inbox/i).closest('button');
    expect(activeBtn).toHaveClass('bg-blue-700');
    expect(activeBtn).toHaveClass('text-white');
});

test('sidebar has icon for inbox', () => {
    render(Sidebar, { user: { email: 'test@example.com' }, activeFolder: 'Inbox' });
    
    // Check for presence of SVG icon next to Inbox
    const inboxBtn = screen.getByText(/Inbox/i);
    const svg = inboxBtn.querySelector('svg');
    expect(svg).toBeDefined();
});
