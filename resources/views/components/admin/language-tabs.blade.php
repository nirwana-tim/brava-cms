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
                :style="langTab === 'id' ? 'background-color: var(--btn-primary-bg); color: var(--btn-primary-text); font-weight: 600; box-shadow: 0 1px 3px rgba(0,0,0,0.2);' : 'background-color: var(--btn-secondary-bg); color: var(--btn-secondary-text); border: 1px solid var(--btn-secondary-border);'"
                class="px-4 py-2 text-sm rounded-lg transition flex items-center gap-2 cursor-pointer">
            Bahasa Indonesia (Default)
        </button>
        <button type="button"
                @click="setTab('en')"
                :style="langTab === 'en' ? 'background-color: var(--btn-primary-bg); color: var(--btn-primary-text); font-weight: 600; box-shadow: 0 1px 3px rgba(0,0,0,0.2);' : 'background-color: var(--btn-secondary-bg); color: var(--btn-secondary-text); border: 1px solid var(--btn-secondary-border);'"
                class="px-4 py-2 text-sm rounded-lg transition flex items-center gap-2 cursor-pointer">
            English (Inggris)
        </button>
    </div>

    {{ $slot }}
</div>
