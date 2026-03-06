<script>
    let { user } = $props();
</script>

<div class="flex h-screen bg-gray-50 overflow-hidden">
    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-gray-200 flex flex-col hidden md:flex">
        <div class="p-4 border-b border-gray-200">
            <h1 class="text-xl font-bold text-blue-600">Webmail</h1>
        </div>
        <nav class="flex-1 p-4 space-y-2">
            <div class="px-3 py-2 bg-blue-50 text-blue-700 rounded-md font-medium">Inbox</div>
            <div class="px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-md">Sent</div>
            <div class="px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-md">Drafts</div>
            <div class="px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-md">Trash</div>
        </nav>
        <div class="p-4 border-t border-gray-200 text-sm text-gray-500">
            {user.email}
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col overflow-hidden">
        <!-- Top Toolbar -->
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6">
            <div class="flex items-center space-x-4">
                <button class="md:hidden text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <div class="text-lg font-medium text-gray-800">Inbox</div>
            </div>
            <div class="flex items-center space-x-4">
                <form action="/logout" method="POST">
                    <input type="hidden" name="_token" value={window.csrfToken}>
                    <button type="submit" class="text-gray-600 hover:text-gray-800 text-sm font-medium">Logout</button>
                </form>
            </div>
        </header>

        <!-- Email List and Reading Pane Container -->
        <div class="flex-1 flex overflow-hidden">
            <!-- Email List -->
            <section class="w-full md:w-1/3 lg:w-1/4 bg-white border-r border-gray-200 overflow-y-auto">
                <div class="p-4 text-center text-gray-500 italic mt-10">
                    No emails found. Syncing...
                </div>
            </section>

            <!-- Reading Pane -->
            <section class="hidden md:block flex-1 bg-white overflow-y-auto p-8">
                <div class="flex flex-col items-center justify-center h-full text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <p class="text-lg">Select an email to read</p>
                </div>
            </section>
        </div>
    </main>
</div>
