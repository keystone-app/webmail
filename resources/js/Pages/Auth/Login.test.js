import { render, screen } from '@testing-library/svelte';
import Login from './Login.svelte';
import { expect, test } from 'vitest';

test('login page renders form fields', () => {
    render(Login, { csrfToken: 'token' });
    
    expect(screen.getByLabelText(/Email Address/i)).toBeInTheDocument();
    expect(screen.getByLabelText(/Password/i)).toBeInTheDocument();
    expect(screen.getByRole('button', { name: /Sign in/i })).toBeInTheDocument();
});

test('login page displays errors', () => {
    const errors = { email: 'Invalid credentials' };
    render(Login, { csrfToken: 'token', errors });
    
    expect(screen.getByText(/Invalid credentials/i)).toBeInTheDocument();
});

test('login page has high-contrast elements', () => {
    render(Login, { csrfToken: 'token' });
    
    const heading = screen.getByText(/Login to Webmail/i);
    expect(heading).toHaveClass('text-gray-950');
});
