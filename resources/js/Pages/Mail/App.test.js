import { render, screen } from '@testing-library/svelte';
import App from './App.svelte';
import { expect, test, vi } from 'vitest';

const mockUser = { email: 'test@example.com' };
const mockEmails = [
    { id: 1, from: 'a@test.com', subject: 'A', date: '2026', body_excerpt: '...', is_read: false },
    { id: 2, from: 'b@test.com', subject: 'B', date: '2026', body_excerpt: '...', is_read: false }
];

// Mock fetch globally
global.fetch = vi.fn().mockImplementation((url) => {
    return Promise.resolve({
        ok: true,
        json: () => Promise.resolve({ data: mockEmails })
    });
});

test('app renders with initial data', async () => {
    render(App, { user: mockUser, emails: mockEmails });
    
    // Check if emails are rendered (wait for them due to mock fetch in onMount)
    const elementsA = await screen.findAllByText('a@test.com');
    expect(elementsA.length).toBeGreaterThan(0);
    
    const elementsB = await screen.findAllByText('b@test.com');
    expect(elementsB.length).toBeGreaterThan(0);
    
    // Check for app shell elements
    expect(screen.getByText('Webmail')).toBeInTheDocument();
    expect(screen.getByText('test@example.com')).toBeInTheDocument();
});
