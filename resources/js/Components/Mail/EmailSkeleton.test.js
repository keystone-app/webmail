import { render, screen } from '@testing-library/svelte';
import EmailList from './EmailList.svelte';
import { expect, test } from 'vitest';

test('email list shows skeletons when loading', () => {
    render(EmailList, { emails: [], isLoading: true });
    
    // Check for multiple skeleton rows (we'll look for animate-pulse class)
    const skeletons = document.querySelectorAll('.animate-pulse');
    expect(skeletons.length).toBeGreaterThan(0);
});
