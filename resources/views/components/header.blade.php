<div x-data="{ show: false }"
    class="sticky top-0 w-full bg-white p-4 border-b border-gray-500 space-y-4 z-10 sm:p-6 sm:space-y-6 lg:p-8 lg:space-y-8">

    <div class="flex justify-between items-center">
        <div class="inline-flex shadow-lg hover:shadow-2xl transition ease-in-out duration-300">
            <a href="https://github.com/alexjustesen/lasso-ci-cd" class="flex items-center px-4 py-2 border border-gray-600 text-base font-medium text-gray-700 bg-white hover:bg-gray-700 hover:border-gray-800 hover:text-white focus:outline-none focus:shadow-outline-gray transition ease-in-out duration-300 lg:text-lg" rel="noopener">
                <x-feathericon-github class="-mx-0.5 h-5 w-5" />
                <span class="hidden lg:inline-block lg:ml-2">TL;DR</span>
            </a>
        </div>

        <div class="inline-flex shadow-lg hover:shadow-2xl transition ease-in-out duration-300">
            <button @click="show = ! show" class="flex items-center px-4 py-2 border border-gray-600 text-base font-medium text-gray-700 bg-white hover:bg-gray-700 hover:border-gray-800 hover:text-white focus:outline-none focus:shadow-outline-gray transition ease-in-out duration-300 lg:text-lg" rel="noopener">
                <x-feathericon-menu class="-mx-0.5 h-5 w-5" />
                <span class="hidden lg:inline-block lg:ml-2">Menu</span>
            </button>
        </div>
    </div>

    <article x-cloak x-show="show" class="prose max-w-none lg:prose-xl">
        <ul>
            <li><a @click="show = false" href="#home">Home</a></li>
            <li><a @click="show = false" href="#getting-started">Getting Started</a></li>
            <li><a @click="show = false" href="#configuring">Configuring</a></li>
            <li><a @click="show = false" href="#github-actions">GitHub Actions</a></li>
            <li><a @click="show = false" href="#deployment">Deployment</a></li>
            <li><a @click="show = false" href="#disclaimer">Disclaimer</a></li>
            <li><a @click="show = false" href="#about-me">About Me</a></li>
        </ul>
    </article>
</div>
