<script>
    let { onClose } = $props();
    
    let to = $state('');
    let cc = $state('');
    let bcc = $state('');
    let subject = $state('');
    
    let showCc = $state(false);
    let showBcc = $state(false);
</script>

<div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 md:p-10">
    <!-- Backdrop -->
    <button 
        class="absolute inset-0 bg-slate-900/60 backdrop-blur-md transition-opacity border-none cursor-default w-full h-full" 
        onclick={onClose}
        aria-label="Close compose"
    ></button>

    <!-- Modal content -->
    <div class="relative bg-white rounded-lg shadow-2xl shadow-slate-950/20 w-full max-w-4xl h-full max-h-[85vh] flex flex-col overflow-hidden border border-slate-200">
        <div class="flex items-center justify-between p-4 border-b border-slate-100 bg-slate-50/50">
            <h2 class="text-lg font-bold text-gray-950">New Message</h2>
            <button 
                onclick={onClose}
                class="text-slate-400 hover:text-gray-950 p-1.5 rounded-lg hover:bg-white transition-all border border-transparent hover:border-slate-200"
            >
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-6 space-y-4">
            <div class="space-y-2">
                <div class="flex items-center space-x-2">
                    <label for="to" class="w-16 text-sm font-bold text-slate-500">To</label>
                    <input 
                        type="email" 
                        id="to" 
                        bind:value={to}
                        class="flex-1 border-none focus:ring-0 text-sm font-medium text-gray-950 p-0 placeholder-slate-300"
                        placeholder="recipient@example.com"
                    >
                    <div class="flex items-center space-x-2">
                        <button 
                            onclick={() => showCc = !showCc}
                            class="text-xs font-bold text-slate-400 hover:text-blue-600 transition-colors"
                        >
                            Cc
                        </button>
                        <button 
                            onclick={() => showBcc = !showBcc}
                            class="text-xs font-bold text-slate-400 hover:text-blue-600 transition-colors"
                        >
                            Bcc
                        </button>
                    </div>
                </div>
                <div class="h-px bg-slate-100 w-full"></div>

                {#if showCc}
                    <div class="flex items-center space-x-2">
                        <label for="cc" class="w-16 text-sm font-bold text-slate-500">Cc</label>
                        <input 
                            type="text" 
                            id="cc" 
                            bind:value={cc}
                            class="flex-1 border-none focus:ring-0 text-sm font-medium text-gray-950 p-0"
                        >
                    </div>
                    <div class="h-px bg-slate-100 w-full"></div>
                {/if}

                {#if showBcc}
                    <div class="flex items-center space-x-2">
                        <label for="bcc" class="w-16 text-sm font-bold text-slate-500">Bcc</label>
                        <input 
                            type="text" 
                            id="bcc" 
                            bind:value={bcc}
                            class="flex-1 border-none focus:ring-0 text-sm font-medium text-gray-950 p-0"
                        >
                    </div>
                    <div class="h-px bg-slate-100 w-full"></div>
                {/if}

                <div class="flex items-center space-x-2">
                    <label for="subject" class="w-16 text-sm font-bold text-slate-500">Subject</label>
                    <input 
                        type="text" 
                        id="subject" 
                        bind:value={subject}
                        class="flex-1 border-none focus:ring-0 text-sm font-bold text-gray-950 p-0 placeholder-slate-300"
                        placeholder="Enter subject"
                    >
                </div>
                <div class="h-px bg-slate-100 w-full"></div>
            </div>

            <!-- Tiptap Editor Placeholder -->
            <div class="min-h-[300px] prose prose-slate max-w-none focus:outline-none">
                <p class="text-slate-400 italic">Rich text editor will be here...</p>
            </div>
        </div>

        <div class="p-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <button class="p-2 text-slate-400 hover:text-blue-600 hover:bg-white rounded-lg transition-all border border-transparent hover:border-slate-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 2 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                    </svg>
                </button>
            </div>
            <div class="flex items-center space-x-3">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest mr-4">Draft saved</span>
                <button 
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-lg shadow-blue-900/20 transition-all active:scale-[0.98]"
                >
                    Send
                </button>
            </div>
        </div>
    </div>
</div>
