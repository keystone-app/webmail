<script>
    let { user, activeFolder = $bindable('Inbox'), onCompose } = $props();
    let folders = [
        { name: 'Inbox', icon: 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z' },
        { name: 'Sent', icon: 'M12 19l9 2-9-18-9 18 9-2zm0 0v-8' }, 
        { name: 'Drafts', icon: 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z' },
        { name: 'Trash', icon: 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16' }
    ];
</script>

<aside class="w-64 bg-slate-900 border-r border-slate-800 flex flex-col hidden md:flex h-full text-slate-300">
    <div class="p-6 border-b border-slate-800">
        <div class="flex items-center space-x-3 mb-6">
            <div class="bg-blue-600 p-2 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
            <h1 class="text-xl font-bold text-white tracking-tight">Webmail</h1>
        </div>

        <button 
            onclick={onCompose}
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg flex items-center justify-center space-x-2 shadow-lg shadow-blue-900/20 transition-all active:scale-[0.98]"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            <span>Compose</span>
        </button>
    </div>
    
    <nav class="flex-1 p-4 space-y-1">
        {#each folders as folder}
            <button 
                class="w-full text-left px-4 py-2.5 rounded-lg font-semibold transition-all flex items-center space-x-3 group {activeFolder === folder.name ? 'bg-blue-700 text-white shadow-lg shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white text-slate-400'}"
                onclick={() => activeFolder = folder.name}
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 {activeFolder === folder.name ? 'text-blue-200' : 'text-slate-500 group-hover:text-slate-300'}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d={folder.icon} />
                </svg>
                <span>{folder.name}</span>
            </button>
        {/each}
    </nav>

    <div class="p-6 border-t border-slate-800 bg-slate-900/50 mt-auto">
        <div class="flex items-center space-x-3">
            <div class="h-8 w-8 rounded-full bg-slate-700 flex items-center justify-center text-xs font-bold text-slate-300">
                {user.email.substring(0, 1).toUpperCase()}
            </div>
            <div class="text-sm font-medium text-slate-400 truncate">
                {user.email}
            </div>
        </div>
    </div>
</aside>
