<div class="text-xs text-center text-white lg:text-sm">
    @if (config('app.env') == 'production')
        Last updated on <span class="font-medium text-emerald-400">{{ \Carbon\Carbon::parse(\App\Application::DATE)->format('F jS, Y') }}</span> as <span class="font-medium text-emerald-400">v{{ \App\Application::VERSION }}</span>.
    @else
        Last updated on 🤷‍♂️, this is the <span class="font-medium text-emerald-400">{{ config('app.env') }}</span> environment.
    @endif
</div>
