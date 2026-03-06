import { render, screen } from '@testing-library/svelte';
import Toolbar from './Toolbar.svelte';
import { expect, test } from 'vitest';

test('toolbar has high-contrast title', () => {
    render(Toolbar, { activeFolder: 'Inbox' });
    
    const title = screen.getByText(/Inbox/i);
    expect(title).toHaveClass('text-gray-950');
    expect(title).toHaveClass('font-bold');
});

test('toolbar has stylized logout button', () => {
    render(Toolbar, { activeFolder: 'Inbox' });
    
    const logoutBtn = screen.getByRole('button', { name: /Logout/i });
    expect(logoutBtn).toHaveClass('text-slate-600');
    expect(logoutBtn).toHaveClass('hover:text-blue-700');
});
