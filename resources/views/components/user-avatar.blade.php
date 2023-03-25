@props(['src'])

<div {{ $attributes->merge(['class' => 'w-12 h-12 rounded-full overflow-hidden']) }}>
    <img src="{{ $src }}" alt="Avatar" class="w-full h-full object-cover">
</div>