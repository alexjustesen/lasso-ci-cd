<div class="text-xs text-center text-white lg:text-sm">
    Last updated on <span class="font-medium text-emerald-400">{{ \Carbon\Carbon::parse(\App\Application::DATE)->format('F jS, Y') }}</span> as <span class="font-medium text-emerald-400">v{{ \App\Application::VERSION }}</span>.
</div>
