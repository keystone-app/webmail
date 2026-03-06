import { render, screen, fireEvent, waitFor } from '@testing-library/svelte';
import Composer from './Composer.svelte';
import { expect, test, vi } from 'vitest';

// Mock fetch globally
global.fetch = vi.fn().mockImplementation((url) =>
    Promise.resolve({
        ok: true,
        json: () => Promise.resolve({ id: url.includes('/api/drafts/') ? parseInt(url.split('/').pop()) : 123 })
    })
);

test('composer renders with initial fields', () => {
    render(Composer, { onClose: vi.fn() });
    
    expect(screen.getByText('New Message')).toBeInTheDocument();
    expect(screen.getByPlaceholderText('recipient@example.com')).toBeInTheDocument();
    expect(screen.getByPlaceholderText('Enter subject')).toBeInTheDocument();
});

test('composer can toggle CC and BCC fields', async () => {
    render(Composer, { onClose: vi.fn() });
    
    const ccBtn = screen.getByText('Cc');
    const bccBtn = screen.getByText('Bcc');
    
    await fireEvent.click(ccBtn);
    expect(screen.getByLabelText('Cc')).toBeInTheDocument();
    
    await fireEvent.click(bccBtn);
    expect(screen.getByLabelText('Bcc')).toBeInTheDocument();
});

test('composer triggers auto-save', async () => {
    vi.useFakeTimers();
    render(Composer, { onClose: vi.fn() });
    
    const subjectInput = screen.getByPlaceholderText('Enter subject');
    await fireEvent.input(subjectInput, { target: { value: 'New Subject' } });
    
    // Fast-forward 2 seconds for debounce
    vi.advanceTimersByTime(2000);
    
    await waitFor(() => {
        expect(global.fetch).toHaveBeenCalledWith('/api/drafts', expect.objectContaining({
            method: 'POST',
            body: expect.stringContaining('New Subject')
        }));
    });
    
    vi.useRealTimers();
});
