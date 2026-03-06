<script>
    let { count = 5 } = $props();
</script>

<div class="divide-y divide-gray-100 w-full">
    {#each Array(count) as _}
        <div class="p-4 w-full animate-pulse">
            <div class="flex justify-between items-start mb-2">
                <div class="h-4 bg-slate-200 rounded w-1/3"></div>
                <div class="h-3 bg-slate-100 rounded w-16"></div>
            </div>
            <div class="h-4 bg-slate-200 rounded w-3/4 mb-2"></div>
            <div class="h-3 bg-slate-100 rounded w-full"></div>
        </div>
    {/each}
</div>
