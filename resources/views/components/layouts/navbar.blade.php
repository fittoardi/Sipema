<header class="window-titlebar flex-shrink-0">
    <div class="flex items-center gap-2">
        <span class="font-mono text-sage-gray text-xs">sipenma://workspace/{{ strtolower(auth()->user()?->role ?? 'user') }}</span>
    </div>

    <div class="font-semibold text-deep-moss text-xs">
        {{ $title ?? 'Workspace' }}
    </div>

    <div class="flex items-center gap-3">
        <span class="tag-pill tag-pill-stone text-[10px] font-mono">{{ auth()->user()?->role }}</span>
    </div>
</header>
