<div class="sm:h-full overflow-y-auto w-full" >
    <div class="flex-1 overflow-y-auto">
        <div class="shrink-0 flex items-center p-3 justify-between h-16">
            <a href="{{ route('bots.index') }}">
                <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
            </a>
            <p class="p-3 text-left text-base font-medium text-gray-600 ">LaravelBot</p>
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
        <!-- Sidebar Content -->
        <nav :class="{'block': open, 'hidden': ! open}" class="z-50 sm:flex w-full bg-white">
            <ul class="space-y-1 w-full">
                <x-responsive-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">
                    {{ Auth::user()->name }}
                </x-responsive-nav-link>
                <hr>
                <x-responsive-nav-link :href="route('bots.index')" :active="request()->routeIs('bots.index')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
            </ul>
        </nav>
    </div>
</div>