@props(['title'])

<div {{$attributes->merge(['class' => 'collapseButton sticky p-3 items-center justify-between grid grid-cols-2'])}}>
    <div class="flex flex-col">
        {{ $title }}
    </div>
    <div class="flex flex-col">
        <button class=" text-sm text-right focus:outline-none ">
            <i class="fa-solid fa-ellipsis hover:bg-gray-100"></i>
        </button>
    </div>
</div>