import './bootstrap';
import { mount } from 'svelte';
import Login from './Pages/Auth/Login.svelte';

const el = document.getElementById('app');
if (el) {
    const props = JSON.parse(el.dataset.props || '{}');
    mount(Login, {
        target: el,
        props: props
    });
}
