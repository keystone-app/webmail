<script>
    let { selectedEmail = null } = $props();
</script>

<section class="hidden md:block flex-1 bg-white overflow-y-auto p-8 h-full">
    {#if !selectedEmail}
        <div class="flex flex-col items-center justify-center h-full text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            <p class="text-lg">Select an email to read</p>
        </div>
    {:else}
        <div class="max-w-3xl mx-auto">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">{selectedEmail.subject}</h2>
            <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-6">
                <div>
                    <div class="font-semibold text-gray-800">{selectedEmail.from}</div>
                    <div class="text-sm text-gray-500">to {selectedEmail.to}</div>
                </div>
                <div class="text-sm text-gray-500">{selectedEmail.date}</div>
            </div>
            <div class="prose prose-blue max-w-none text-gray-800">
                {@html selectedEmail.body}
            </div>
        </div>
    {/if}
</section>
