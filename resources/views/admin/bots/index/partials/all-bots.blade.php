<section>
	@forelse ($bots as $bot)
		<x-panel class="sm:rounded-lg my-2">
			<div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
				<div class="flex flex-col">
					<a href="{{ route('bots.show', $bot->id) }}">
						<x-user-contact avatar="{{ $bot->me()->getPhotoLink() }}" firstName="{{ $bot->me()->getFirstName() }}" lastName="{{ $bot->me()->getLastName() }}" description="{{ $bot->me()->getUsername() }}"/>
					</a>
				</div>
				<div class="flex-col sm:flex hidden">
					<span class="text-sm font-medium text-gray-500">Bot ID:</span>
					<span class="text-sm font-medium">{{ $bot->me()->getId() }}</span>
				</div>
				<div class="flex-col sm:flex hidden">
					<span class="text-sm font-medium text-gray-500">Status:</span>
					<span class="text-sm font-medium">@if ($bot->webhookInfo()->getUrl()) <span class="text-green-500" title="{{$bot->webhookInfo()->getUrl()}}"> Work </span> @endif</span>
				</div>
			</div>
		</x-panel>
	@empty
	@endforelse
</section>
