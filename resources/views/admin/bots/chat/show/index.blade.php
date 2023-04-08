@extends('layouts.app')


@section('content')

    <div class="sticky inset-y-0 z-10">
        @include('admin.bots.chat.show.partials.header')
    </div>
    <div class="">
        @include('admin.bots.chat.show.partials.messages')
    </div>

    <x-modal name="send-new-message" focusable title="{{ __('Write a new Message') }}">
        <form method="post" action="{{ route('message.store', $chat->id) }}" class="px-3">
            @csrf
    
            <div class="">
                <x-textarea id="message" name="message" rows="5"></x-textarea>
            </div>
    
            <div class="my-3 flex justify-end">
                <x-primary-button class="ml-3">
                    {{ __('Send') }}
                </x-primary-button>
            </div>
        </form>
    </x-modal>
@endsection

@section('script')
    <script>
        var scrollHeight = Math.max(
            document.body.scrollHeight, document.documentElement.scrollHeight,
            document.body.offsetHeight, document.documentElement.offsetHeight,
            document.body.clientHeight, document.documentElement.clientHeight
        )
        window.scrollTo(0, scrollHeight);
        
        const menuButton = document.getElementById('menuButton');
        const menuList = document.getElementById('menuList');

        menuButton.addEventListener('click', function(event) {
            event.stopPropagation(); // Остановить всплытие клика
            menuList.classList.toggle('hidden');
        });

        document.addEventListener('click', function(event) {
            const isClickInside = menuButton.contains(event.target) || menuList.contains(event.target);
            if (!isClickInside) {
                menuList.classList.add('hidden');
            }
        });
    </script>
@endsection