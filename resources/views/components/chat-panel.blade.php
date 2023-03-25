@props(['chat', "bot"])

<x-panel {{ $attributes->merge(['class' => 'bg-gray-100']) }}>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="flex flex-col">
            
            <a href="{{ route('chats.show', $chat->id) }}">
                <x-user-contact class="text-sm" avatar=" {{ $bot->getTelegram()::getPhoto(['file_id' => $chat->photo]) }} " firstName="{{$chat->first_name ?? $chat->title}}" lastName="{{ $chat->last_name }}" description="{{ $chat->username }}"/>
            </a>
        </div>
    </div>
</x-panel>