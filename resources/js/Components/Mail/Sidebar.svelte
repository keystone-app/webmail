<script>
    let { user, activeFolder = $bindable('Inbox') } = $props();
    let folders = ['Inbox', 'Sent', 'Drafts', 'Trash'];
</script>

<aside class="w-64 bg-white border-r border-gray-200 flex flex-col hidden md:flex h-full">
    <div class="p-4 border-b border-gray-200">
        <h1 class="text-xl font-bold text-blue-600">Webmail</h1>
    </div>
    <nav class="flex-1 p-4 space-y-2">
        {#each folders as folder}
            <button 
                class="w-full text-left px-3 py-2 rounded-md font-medium transition-colors {activeFolder === folder ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-100'}"
                onclick={() => activeFolder = folder}
            >
                {folder}
            </button>
        {/each}
    </nav>
    <div class="p-4 border-t border-gray-200 text-sm text-gray-500 truncate">
        {user.email}
    </div>
</aside>
