<script>
    let { activeFolder = 'Inbox' } = $props();
</script>

<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 w-full">
    <div class="flex items-center space-x-4">
        <button class="md:hidden text-gray-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        <div class="text-lg font-medium text-gray-800">{activeFolder}</div>
    </div>
    <div class="flex items-center space-x-4">
        <form action="/logout" method="POST">
            <input type="hidden" name="_token" value={window.csrfToken}>
            <button type="submit" class="text-gray-600 hover:text-gray-800 text-sm font-medium transition-colors">Logout</button>
        </form>
    </div>
</header>
