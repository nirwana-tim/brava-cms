<div x-data="{ 
        langTab: 'id',
        setTab(tab) {
            this.langTab = tab;
            if (window.tinymce) {
                window.tinymce.triggerSave();
            }
            window.dispatchEvent(new CustomEvent('language-tab-changed', { detail: tab }));
        }
    }" class="space-y-6">
    <div class="flex items-center gap-2 border-b pb-2 mb-6" style="border-color: var(--table-border)">
        <button type="button" 
                @click="setTab('id')" 
                :class="langTab === 'id' ? 'bg-blue-600 text-white font-semibold shadow' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-200'" 
                class="px-4 py-2 text-sm rounded-lg transition flex items-center gap-2 cursor-pointer">
            <span class="text-base">🇮🇩</span> Bahasa Indonesia (Default)
        </button>
        <button type="button" 
                @click="setTab('en')" 
                :class="langTab === 'en' ? 'bg-blue-600 text-white font-semibold shadow' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-200'" 
                class="px-4 py-2 text-sm rounded-lg transition flex items-center gap-2 cursor-pointer">
            <span class="text-base">🇬🇧</span> English (Inggris)
        </button>
    </div>

    {{ $slot }}
</div>
