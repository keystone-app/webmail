<script>
    import EmailSkeleton from './EmailSkeleton.svelte';
    let { emails = [], selectedEmailId = $bindable(null), isFullWidth = false, isLoading = false } = $props();
</script>

<section class="{isFullWidth ? 'w-full' : 'w-full md:w-1/3 lg:w-1/4'} bg-white border-r border-slate-200 overflow-y-auto h-full">
    {#if isLoading}
        <EmailSkeleton count={10} />
    {:else if emails.length === 0}
        <div class="p-8 text-center mt-10">
            <div class="bg-slate-50 rounded-full h-16 w-16 flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
            <p class="text-slate-500 font-medium italic">No emails found in this folder.</p>
        </div>
    {:else}
        <div class="divide-y divide-slate-100">
            {#each emails as email}
                <div class="group relative bg-white transition-all hover:bg-slate-50 {selectedEmailId === email.id ? 'bg-blue-50/50' : ''}">
                    <button 
                        class="w-full p-5 text-left focus:outline-none border-l-4 {selectedEmailId === email.id ? 'border-blue-600' : 'border-transparent group-hover:border-slate-300'}"
                        onclick={() => selectedEmailId = email.id}
                    >
                        <div class="flex justify-between items-start mb-1.5">
                            <span class="font-bold text-gray-950 truncate pr-2 tracking-tight">{email.from}</span>
                            <span class="text-xs font-bold text-slate-400 whitespace-nowrap">{email.date}</span>
                        </div>
                        <div class="text-[15px] {email.is_read ? 'text-slate-600 font-medium' : 'font-extrabold text-gray-950'} truncate mb-1.5 tracking-tight">{email.subject}</div>
                        <div class="text-sm text-slate-500 line-clamp-2 leading-relaxed">{email.body_excerpt || ''}</div>
                    </button>

                    <!-- Contextual Actions -->
                    <div class="absolute right-4 bottom-4 flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity bg-white/90 backdrop-blur-sm p-1 rounded-lg shadow-sm border border-slate-100">
                        <button 
                            class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" 
                            aria-label="Archive"
                            title="Archive"
                            onclick={(e) => { e.stopPropagation(); console.log('Archive', email.id); }}
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            </svg>
                        </button>
                        <button 
                            class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" 
                            aria-label="Delete"
                            title="Delete"
                            onclick={(e) => { e.stopPropagation(); console.log('Delete', email.id); }}
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            {/each}
        </div>
    {/if}
</section>
