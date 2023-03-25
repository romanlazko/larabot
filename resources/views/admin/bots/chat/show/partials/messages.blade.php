<section>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 py-3">
        <div class="px-3">
            {{ $messages->links() }}
        </div>
        
        @foreach ($messages->reverse() as $message)
            @if ($message->from()->first()?->is_bot === 0 OR $message->callback_query()?->first()?->from()->first()?->is_bot === 0 OR $message->sender_chat)
                <x-message-block class="mr-6 ml-1">
                    @if ($message->photo)
                        <x-message-img class="rounded-md" src="{{ $message->photo() }}"/>
                    @endif
                    <x-message-text :message="$message" class="bg-white"/>
                    <x-message-buttons :message="$message"/>
                </x-message-block>
            @else
                <x-message-block class="sm:ml-auto ml-6 mr-1">
                    @if ($message->photo)
                        <x-message-img class="rounded-md" src="{{ $message->photo() }}"/>
                    @endif

                    <x-message-text :message="$message" class="bg-blue-50"/>
                    <x-message-buttons :message="$message"/>
                </x-message-block>
            @endif
        @endforeach

        @if (session('ok') === true)
            <x-small-notification class="bg-green-500" :title="__('Message was successfully sent')"/>
        @elseif (session('ok') === false)
            <x-small-notification class="bg-red-500" :title="session('description')"/>
        @endif
    </div>
</section>

