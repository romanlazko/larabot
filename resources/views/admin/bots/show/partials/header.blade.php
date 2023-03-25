<x-panel class="shadow">
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 ">
        <div class="flex flex-col">
            <x-user-contact  avatar="{{ $bot->me()->getPhotoLink() }}" firstName="{{ $bot->me()->getFirstName() }}" lastName="{{ $bot->me()->getLastName() }}" description="{{ $bot->me()->getUsername() }}"/>
        </div>
        <div class="flex-col sm:flex hidden">
            <span class="text-sm font-medium text-gray-500">Status: @if ($bot->webhookInfo()->getUrl()) <span class="text-green-500"> Work </span> @endif</span>
            <span class="text-sm font-medium text-gray-500">Bot ID: <span class="text-black">{{ $bot->me()->getId() }}</span></span>
        </div>
    </div>
</x-panel>