<script>
    let { csrfToken, errors = {}, old = {} } = $props();
    
    // Ensure errors and old are at least empty objects if they are passed as null
    const safeErrors = $derived(errors || {});
    const safeOld = $derived(old || {});

    let email = $state(safeOld.email || '');
    let password = $state('');
</script>

<div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
    <div class="max-w-md w-full bg-white rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">Login to Webmail</h2>

        <form action="/login" method="POST" class="space-y-6">
            <input type="hidden" name="_token" value={csrfToken}>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    bind:value={email}
                    required 
                    class="mt-1 block w-full px-3 py-2 border {safeErrors.email ? 'border-red-500' : 'border-gray-300'} rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                >
                {#if safeErrors.email}
                    <p class="mt-2 text-sm text-red-600">{safeErrors.email}</p>
                {/if}
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    bind:value={password}
                    required 
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                >
            </div>

            <div>
                <button 
                    type="submit" 
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                    Sign in
                </button>
            </div>
        </form>
    </div>
</div>
