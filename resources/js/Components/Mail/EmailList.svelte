<script>
    import EmailSkeleton from './EmailSkeleton.svelte';
    let { emails = [], selectedEmail = $bindable(), isFullWidth = false, isLoading = false } = $props();
</script>

<section class="{isFullWidth ? 'w-full' : 'w-full md:w-1/3 lg:w-1/4'} bg-white border-r border-gray-200 overflow-y-auto h-full">
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
        <div class="divide-y divide-gray-100">
            {#each emails as email}
                <button 
                    class="w-full p-4 text-left hover:bg-blue-50 transition-colors focus:outline-none focus:bg-blue-50 group border-l-4 {selectedEmail?.id === email.id ? 'border-blue-600 bg-blue-50' : 'border-transparent'}"
                    onclick={() => selectedEmail = email}
                >
                    <div class="flex justify-between items-start mb-1">
                        <span class="font-bold text-gray-950 truncate pr-2">{email.from}</span>
                        <span class="text-xs font-semibold text-slate-500 whitespace-nowrap">{email.date}</span>
                    </div>
                    <div class="text-sm {email.is_read ? 'text-slate-600' : 'font-extrabold text-gray-950'} truncate mb-1">{email.subject}</div>
                    <div class="text-xs text-slate-500 line-clamp-2">{email.body_excerpt || ''}</div>
                </button>
            {/each}
        </div>
    {/if}
</section>
