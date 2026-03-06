<script>
    import Sidebar from '../../Components/Mail/Sidebar.svelte';
    import Toolbar from '../../Components/Mail/Toolbar.svelte';
    import EmailList from '../../Components/Mail/EmailList.svelte';
    import ReadingPane from '../../Components/Mail/ReadingPane.svelte';
    import { onMount } from 'svelte';

    let { user } = $props();
    
    let activeFolder = $state('Inbox');
    let emails = $state([]);
    let selectedEmail = $state(null);
    let isModalOpen = $state(false);
    let isLoading = $state(false);

    // Fetch emails from the API
    async function fetchEmails(folder = 'Inbox') {
        isLoading = true;
        try {
            const response = await fetch(`/api/emails?folder=${folder}`);
            const result = await response.json();
            emails = result.data;
        } catch (error) {
            console.error('Failed to fetch emails:', error);
        } finally {
            isLoading = false;
        }
    }

    async function fetchEmailDetails(id) {
        try {
            const response = await fetch(`/api/emails/${id}`);
            const result = await response.json();
            selectedEmail = result.data;
        } catch (error) {
            console.error('Failed to fetch email details:', error);
        }
    }

    onMount(() => {
        fetchEmails(activeFolder);
    });

    // Re-fetch when activeFolder changes
    $effect(() => {
        fetchEmails(activeFolder);
    });

    // Watch for selectedEmail changes to open the modal and fetch details
    $effect(() => {
        if (selectedEmail && !selectedEmail.body) {
            fetchEmailDetails(selectedEmail.id);
            isModalOpen = true;
        } else if (selectedEmail) {
            isModalOpen = true;
        }
    });

    function closeModal() {
        isModalOpen = false;
        // Optional: clear selected email when closing
        // selectedEmail = null;
    }
</script>

<div class="flex h-screen bg-gray-50 overflow-hidden">
    <Sidebar {user} bind:activeFolder />

    <div class="flex-1 flex flex-col overflow-hidden">
        <Toolbar {activeFolder} />

        <div class="flex-1 flex overflow-hidden">
            <!-- Email List now takes full width -->
            <EmailList {emails} bind:selectedEmail isFullWidth={true} {isLoading} />
        </div>
    </div>
</div>

<!-- Reading Pane Modal -->
{#if isModalOpen && selectedEmail}
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6">
        <!-- Backdrop -->
        <button 
            class="absolute inset-0 bg-gray-500/75 transition-opacity border-none cursor-default w-full h-full" 
            onclick={closeModal}
            aria-label="Close modal"
        ></button>

        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow-xl w-full max-w-5xl h-full max-h-[90vh] flex flex-col overflow-hidden">
            <div class="flex justify-end p-4 border-b border-gray-100">
                <button 
                    onclick={closeModal}
                    class="text-gray-400 hover:text-gray-500 transition-colors"
                >
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="flex-1 overflow-y-auto">
                <ReadingPane {selectedEmail} />
            </div>
        </div>
    </div>
{/if}
