
<x-panel class="shadow">
    <div class="flex sm:grid gap-4 sm:grid-cols-3 items-center justify-between">
        
        <div class="flex flex-auto items-center ">
            <a href="{{ route('bots.show', $chat->bot_id) }}" class="w-10 h-full">
                <i class="fa-solid fa-chevron-left"></i>
            </a>
            <x-user-contact class="" avatar="{{$chat->photo()}}" firstName="{{$chat->first_name ?? $chat->title}}" lastName="{{ $chat->last_name }}" description="{{ $chat->username }}"/>
            <form method="POST" action="{{ route('chats.update', $chat->id) }}">
                @csrf
                @method('PUT')
                <x-badge color="green" class="ml-2 appearance-none bg-transparent pr-5 bg-no-repeat bg-right" onchange="this.form.submit()" name="role">
                    <option @selected($chat->role === 'user') value="user">user</option>
                    <option @selected($chat->role === 'admin') value="admin">admin</option>
                    <option @selected($chat->role === 'blocked') value="blocked">blocked</option>
                </x-badge> 
            </form>
            
        </div>
        <div class="sm:flex flex-col hidden">
            <x-secondary-button
                x-data=""
                x-on:click.prevent="$dispatch('open-modal', 'send-new-message')"
                class="bg-indigo-400 border-0 text-center text-white hover:bg-indigo-500 block"
            >
                {{ __('New message') }}
            </x-secondary-button>
        </div>
        <div class="flex flex-col">
            <button id="menuButton" class="text-sm text-right focus:outline-none ">
                <i class="fa-solid fa-ellipsis hover:bg-gray-100"></i>
            </button>
            <div id="menuList" class="hidden absolute right-1 top-16 w-56 mt-2 origin-top-right bg-white divide-y divide-gray-100 rounded-md shadow-lg ring-1 ring-black ring-opacity-5" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                <div class="py-1" role="none">
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">Заблокировать</a>
                    <a href="#" class="sm:hidden block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 open-modal " role="menuitem" x-data=""
                    x-on:click.prevent="$dispatch('open-modal', 'send-new-message')" >Написать сообщение</a>
                </div>
            </div>
        </div>
    </div>
</x-panel>



  