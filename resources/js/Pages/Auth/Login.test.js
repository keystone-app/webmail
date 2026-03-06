import { render, screen } from '@testing-library/svelte';
import Login from './Login.svelte';
import { expect, test } from 'vitest';

test('login page has high-contrast elements', () => {
    render(Login, { csrfToken: 'token' });
    
    // Check for high-contrast heading
    const heading = screen.getByText(/Login to Webmail/i);
    expect(heading).toHaveClass('text-gray-950');
    expect(heading).toHaveClass('font-extrabold');
});

test('login button has high-contrast colors', () => {
    render(Login, { csrfToken: 'token' });
    
    const button = screen.getByRole('button', { name: /Sign in/i });
    expect(button).toHaveClass('bg-blue-700'); // Higher contrast than blue-600
});
