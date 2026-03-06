<script>
    let { emails = [] } = $props();
</script>

<section class="w-full md:w-1/3 lg:w-1/4 bg-white border-r border-gray-200 overflow-y-auto h-full">
    {#if emails.length === 0}
        <div class="p-4 text-center text-gray-500 italic mt-10">
            No emails found. Syncing...
        </div>
    {:else}
        <div class="divide-y divide-gray-100">
            {#each emails as email}
                <button class="w-full p-4 text-left hover:bg-blue-50 transition-colors focus:outline-none focus:bg-blue-50 group border-l-4 border-transparent hover:border-blue-400">
                    <div class="flex justify-between items-start mb-1">
                        <span class="font-semibold text-gray-900 truncate pr-2">{email.from}</span>
                        <span class="text-xs text-gray-500 whitespace-nowrap">{email.date}</span>
                    </div>
                    <div class="text-sm font-medium text-gray-800 truncate mb-1">{email.subject}</div>
                    <div class="text-xs text-gray-500 line-clamp-2">{email.body_excerpt || ''}</div>
                </button>
            {/each}
        </div>
    {/if}
</section>
