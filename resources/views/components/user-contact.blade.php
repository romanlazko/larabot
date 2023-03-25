@props(['avatar', 'firstName', 'lastName', 'description'])

<div {{ $attributes->merge(['class' => 'flex items-start']) }}>
    
    <div class="mr-4">
        <x-user-avatar :src="$avatar" />
    </div>
    <div>
        <div class="w-full text-sm font-medium text-gray-900">{{ $firstName }} {{ $lastName }}</div>
        <a href="" class="w-full text-sm font-medium text-gray-500 mb-1">{{ $description }}</a>
    </div>
    
</div>