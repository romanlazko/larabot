@props(['message'])

@if ($message->callback_query()->first()->data ?? $message->text ?? $message->caption )
    <div {{ $attributes->merge(['class' => 'shadow rounded-lg p-3 ']) }}>
        <p class="text-sm font-medium text-gray-500">
            {{ $message->callback_query()?->first()?->from()->first()?->first_name ?? $message->from()->first()?->first_name}} {{ $message->callback_query()?->first()?->from()->first()?->last_name ?? $message->from()->first()?->last_name}} 
        </p>
        {{ $message->callback_query()->first()->data ?? $message->text ?? $message->caption }}
        <br>
        <small>{{ $message->created_at->format('d.m.Y H:i:s') }}</small>
    </div>
@endif
