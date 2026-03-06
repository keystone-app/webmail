import '@testing-library/jest-dom';
import { vi } from 'vitest';

// Basic fetch mock to avoid URL parsing errors in JSDOM
global.fetch = vi.fn(() => 
    Promise.resolve({
        json: () => Promise.resolve({ data: [] }),
    })
);
