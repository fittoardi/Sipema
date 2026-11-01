<header class="window-titlebar flex-shrink-0 flex items-center justify-between">
    <div class="flex items-center gap-2">
        <button @click="sidebarOpen = true" class="lg:hidden p-1 mr-1 text-sage-gray hover:text-deep-moss">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <span class="font-mono text-sage-gray text-xs truncate max-w-[150px] sm:max-w-none">sipenma://workspace/{{ strtolower(auth()->user()?->role ?? 'user') }}</span>
    </div>

    <div class="font-semibold text-deep-moss text-xs">
        {{ $title ?? 'Workspace' }}
    </div>

    <div class="flex items-center gap-3">
        <span class="tag-pill tag-pill-stone text-[10px] font-mono">{{ auth()->user()?->role }}</span>
    </div>
</header>
