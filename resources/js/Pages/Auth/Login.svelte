<script>
    let { csrfToken, errors = {}, old = {} } = $props();
    
    const safeErrors = $derived(errors || {});
    const safeOld = $derived(old || {});

    let email = $state('');
    
    $effect(() => {
        if (safeOld.email) {
            email = safeOld.email;
        }
    });
    let password = $state('');
</script>

<div class="min-h-screen flex items-center justify-center bg-slate-50 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-lg shadow-xl border border-slate-100">
        <div>
            <div class="flex justify-center">
                <div class="bg-blue-700 p-3 rounded-lg shadow-blue-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-950 tracking-tight">Login to Webmail</h2>
            <p class="mt-2 text-center text-sm text-slate-600">
                Enter your IMAP credentials to continue
            </p>
        </div>

        <form action="/login" method="POST" class="mt-8 space-y-6">
            <input type="hidden" name="_token" value={csrfToken}>

            <div class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-950">Email Address</label>
                    <div class="mt-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                            </svg>
                        </div>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            bind:value={email}
                            required 
                            placeholder="you@example.com"
                            class="block w-full pl-10 pr-3 py-3 border {safeErrors.email ? 'border-red-500 ring-red-500' : 'border-slate-200 focus:ring-blue-600 focus:border-blue-600'} rounded-lg shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 transition-all text-gray-950"
                        >
                    </div>
                    {#if safeErrors.email}
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            {safeErrors.email}
                        </p>
                    {/if}
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-950">Password</label>
                    <div class="mt-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            bind:value={password}
                            required 
                            placeholder="••••••••"
                            class="block w-full pl-10 pr-3 py-3 border border-slate-200 rounded-lg shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-all text-gray-950"
                        >
                    </div>
                </div>
            </div>

            <div>
                <button 
                    type="submit" 
                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-lg text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 transition-all shadow-lg shadow-blue-200"
                >
                    Sign in
                </button>
            </div>
        </form>
    </div>
</div>
