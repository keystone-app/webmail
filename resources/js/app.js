import './bootstrap';
import { mount } from 'svelte';
import Login from './Pages/Auth/Login.svelte';
import MailApp from './Pages/Mail/App.svelte';

const el = document.getElementById('app');
if (el) {
    const props = JSON.parse(el.dataset.props || '{}');
    
    // Make CSRF token available globally for Svelte components
    if (props.csrfToken) {
        window.csrfToken = props.csrfToken;
    }

    let component;
    
    // Handle specific routes or server-directed components
    switch (props.component) {
        case 'Login':
            component = Login;
            break;
        case 'MailApp':
            component = MailApp;
            break;
        default:
            // Fallback for direct routing if needed
            if (window.location.pathname === '/login') {
                component = Login;
            } else {
                component = MailApp;
            }
    }

    if (component) {
        mount(component, {
            target: el,
            props: props
        });
    }
}
