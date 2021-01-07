<div x-data="{ show: false }" class="sticky top-0 z-10 -mx-4 -my-3 sm:-m-6 lg:-m-8">
    <div class="px-4 py-4 bg-white border-b border-gray-500 sm:p-6 lg:p-8">
        <div class="flex items-center justify-between">
            <div class="inline-flex transition duration-300 ease-in-out shadow-lg hover:shadow-2xl">
                <a href="https://github.com/alexjustesen/lasso-ci-cd" class="flex items-center px-4 py-2 text-base font-medium text-gray-700 transition duration-300 ease-in-out bg-white border border-gray-600 hover:bg-gray-700 hover:border-gray-800 hover:text-white focus:outline-none focus:shadow-outline-gray lg:text-lg" rel="noopener">
                    <x-feathericon-github class="-mx-0.5 h-5 w-5" />
                    <span class="hidden lg:inline-block lg:ml-2">TL;DR</span>
                </a>
            </div>

            <div class="inline-flex transition duration-300 ease-in-out shadow-lg hover:shadow-2xl">
                <button @click="show = ! show" class="flex items-center px-4 py-2 text-base font-medium text-gray-700 transition duration-300 ease-in-out bg-white border border-gray-600 hover:bg-gray-700 hover:border-gray-800 hover:text-white focus:outline-none focus:shadow-outline-gray lg:text-lg" rel="noopener">
                    <x-feathericon-menu class="-mx-0.5 h-5 w-5" />
                    <span class="hidden lg:inline-block lg:ml-2">Menu</span>
                </button>
            </div>
        </div>

        <div x-cloak x-show="show">
            <article class="prose max-w-none lg:prose-xl">
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
    </div>
</div>
