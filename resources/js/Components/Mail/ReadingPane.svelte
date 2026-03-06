<script>
    let { selectedEmail = null } = $props();
    
    let actions = [
        { name: 'Reply', icon: 'M3 10h10a8 8 0 018 8v2M3 10l5 5m-5-5l5-5' },
        { name: 'Forward', icon: 'M21 10H11a8 8 0 00-8 8v2M21 10l-5 5m5-5l-5-5' },
        { name: 'Delete', icon: 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16' }
    ];
</script>

<section class="flex-1 bg-white overflow-y-auto p-6 sm:p-10 h-full">
    {#if !selectedEmail}
        <div class="flex flex-col items-center justify-center h-full text-slate-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 mb-6 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            <p class="text-xl font-medium tracking-tight">Select an email to read</p>
        </div>
    {:else}
        <div class="max-w-4xl mx-auto">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-8 mb-8">
                <div class="space-y-1">
                    <h2 class="text-3xl font-extrabold text-gray-950 tracking-tight leading-tight">{selectedEmail.subject}</h2>
                    <div class="flex items-center space-x-2 text-sm">
                        <span class="font-bold text-gray-950">{selectedEmail.from}</span>
                        <span class="text-slate-400">to</span>
                        <span class="font-medium text-slate-600">{selectedEmail.to}</span>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    {#each actions as action}
                        <button 
                            class="p-2.5 text-slate-500 hover:text-blue-700 hover:bg-blue-50 rounded-xl transition-all border border-transparent hover:border-blue-100" 
                            aria-label={action.name}
                            title={action.name}
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d={action.icon} />
                            </svg>
                        </button>
                    {/each}
                </div>
            </div>

            <div class="prose prose-slate prose-lg max-w-none text-gray-900 leading-relaxed">
                <div class="bg-slate-50/50 rounded-2xl p-6 sm:p-8 border border-slate-100">
                    {@html selectedEmail.body}
                </div>
            </div>
            
            <div class="mt-12 pt-8 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-slate-400 uppercase tracking-widest">
                <span>Received on {selectedEmail.date}</span>
                <span>Security verified</span>
            </div>
        </div>
    {/if}
</section>
