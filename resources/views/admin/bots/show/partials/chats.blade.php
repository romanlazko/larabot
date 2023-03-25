<div class="">
    <div class="flex flex-col collapse-content">
        {{-- <x-sticky-header-with-collapse-button class="bg-white p-2 top-20" title="Чаты"/> --}}
        <div class="content">
            @forelse ($chats as $chat)
                <x-chat-panel class="bg-gray-100 hover:bg-white hover:shadow-lg" :chat="$chat" :bot="$bot"/>
            @empty
                <p>No chats found.</p>
            @endforelse
        </div>
        
        
    </div>
    <div class="bottom-0 p-3">
        {{ $chats->links() }}
    </div>
    
</div>