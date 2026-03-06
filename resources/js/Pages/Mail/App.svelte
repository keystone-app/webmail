<script>
    import Sidebar from '../../Components/Mail/Sidebar.svelte';
    import Toolbar from '../../Components/Mail/Toolbar.svelte';
    import EmailList from '../../Components/Mail/EmailList.svelte';
    import ReadingPane from '../../Components/Mail/ReadingPane.svelte';

    let { user } = $props();
    
    let activeFolder = $state('Inbox');
    let emails = $state([]);
    let selectedEmail = $state(null);
</script>

<div class="flex h-screen bg-gray-50 overflow-hidden">
    <Sidebar {user} bind:activeFolder />

    <div class="flex-1 flex flex-col overflow-hidden">
        <Toolbar {activeFolder} />

        <div class="flex-1 flex overflow-hidden">
            <EmailList {emails} bind:selectedEmail />
            <ReadingPane {selectedEmail} />
        </div>
    </div>
</div>
